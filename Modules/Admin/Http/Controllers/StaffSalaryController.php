<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\StaffSalary;
use Modules\Admin\Models\SalaryDisbursement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StaffSalaryController extends Controller
{
    public function index(Request $request)
    {
        // Selected month filter (default to current month: YYYY-MM)
        $selectedMonthStr = $request->input('month', Carbon::now()->format('Y-m'));
        try {
            $selectedMonth = Carbon::createFromFormat('Y-m', $selectedMonthStr)->startOfMonth();
        } catch (\Exception $e) {
            $selectedMonth = Carbon::now()->startOfMonth();
            $selectedMonthStr = $selectedMonth->format('Y-m');
        }

        // 1. Core Payroll Metrics (Current Active Commitments)
        $activeSalariesQuery = StaffSalary::where('status', 'active');
        $totalActiveSalaries = (float) (clone $activeSalariesQuery)->sum('amount');
        $activeStaffCount = (int) (clone $activeSalariesQuery)->count();
        $averageSalary = $activeStaffCount > 0 ? ($totalActiveSalaries / $activeStaffCount) : 0;
        $annualProjection = $totalActiveSalaries * 12;

        // 2. Actual Disbursements for the Selected Month
        $disbursedThisMonth = (float) SalaryDisbursement::whereIn('status', ['successful', 'completed'])
            ->whereYear('payment_date', $selectedMonth->year)
            ->whereMonth('payment_date', $selectedMonth->month)
            ->sum('amount');

        $remainingThisMonth = max(0, $totalActiveSalaries - $disbursedThisMonth);
        $payoutPercentage = $totalActiveSalaries > 0 
            ? min(100, round(($disbursedThisMonth / $totalActiveSalaries) * 100, 1)) 
            : 0;

        // 3. Month-by-month Salary Expense Trend (Last 6 Months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i)->startOfMonth();
            $disbursedInM = (float) SalaryDisbursement::whereIn('status', ['successful', 'completed'])
                ->whereYear('payment_date', $m->year)
                ->whereMonth('payment_date', $m->month)
                ->sum('amount');

            $monthlyTrend[] = [
                'month_key' => $m->format('Y-m'),
                'month_name' => $m->format('M Y'),
                'is_current' => $m->format('Y-m') === Carbon::now()->format('Y-m'),
                'disbursed' => $disbursedInM,
                'commitment' => $totalActiveSalaries, // current baseline commitment
            ];
        }

        // 4. Staff Salaries List with Filters
        $query = StaffSalary::with(['user.bankAccounts', 'creator'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('department', $request->department);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($sub) use ($search) {
                $sub->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('designation', 'like', "%{$search}%");
            });
        }

        $salaries = $query->paginate(20)->withQueryString();

        // 5. Eligible Team Members for the "Record Salary" Modal
        $allUsers = User::where('status', 'active')
            ->orderBy('name')
            ->get();

        $departments = User::whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->pluck('department');

        return view('admin::salaries.staff-salaries', compact(
            'salaries',
            'totalActiveSalaries',
            'activeStaffCount',
            'averageSalary',
            'annualProjection',
            'disbursedThisMonth',
            'remainingThisMonth',
            'payoutPercentage',
            'selectedMonth',
            'selectedMonthStr',
            'monthlyTrend',
            'allUsers',
            'departments'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'payment_cycle' => 'required|in:monthly,bi-weekly,weekly',
            'payment_day' => 'nullable|integer|min:1|max:31',
            'effective_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,on_hold',
            'designation' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = User::findOrFail($validated['user_id']);

        // Default designation to user role/department if empty
        if (empty($validated['designation'])) {
            $validated['designation'] = ucfirst(str_replace('_', ' ', $user->role ?? 'Team Member'));
        }

        $validated['created_by'] = auth()->id();
        $validated['effective_date'] = $validated['effective_date'] ?? now()->toDateString();
        $validated['payment_day'] = $validated['payment_day'] ?? 28;

        StaffSalary::updateOrCreate(
            ['user_id' => $validated['user_id']],
            $validated
        );

        return redirect()->route('admin.accounting.payroll.index')
            ->with('success', "Salary for {$user->name} recorded successfully.");
    }

    public function update(Request $request, StaffSalary $payroll)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'payment_cycle' => 'required|in:monthly,bi-weekly,weekly',
            'payment_day' => 'nullable|integer|min:1|max:31',
            'effective_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,on_hold',
            'designation' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $payroll->update($validated);

        return redirect()->route('admin.accounting.payroll.index')
            ->with('success', "Salary for {$payroll->user->name} updated successfully.");
    }

    public function toggleStatus(StaffSalary $payroll)
    {
        $newStatus = $payroll->status === 'active' ? 'inactive' : 'active';
        $payroll->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Salary for {$payroll->user->name} has been {$statusText}.");
    }

    public function destroy(StaffSalary $payroll)
    {
        $userName = $payroll->user->name ?? 'Team Member';
        $payroll->delete();

        return redirect()->route('admin.accounting.payroll.index')
            ->with('success', "Salary record for {$userName} deleted successfully.");
    }
}
