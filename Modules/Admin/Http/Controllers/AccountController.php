<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountCategory;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::with('category')->withCount('transactions')->latest()->paginate(20);
        return view('admin::accounting.accounts.index', compact('accounts'));
    }

    public function create()
    {
        $categories = AccountCategory::orderBy('name')->get();
        return view('admin::accounting.accounts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:account_categories,id',
            'code' => 'required|string|unique:accounts,code',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'balance' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        Account::create($validated);

        return redirect()->route('admin.accounting.accounts.index')
            ->with('success', 'Account created successfully.');
    }

    public function show(Account $account)
    {
        $account->load(['category', 'transactions']);
        return view('admin::accounting.accounts.show', compact('account'));
    }

    public function edit(Account $account)
    {
        $categories = AccountCategory::orderBy('name')->get();
        return view('admin::accounting.accounts.edit', compact('account', 'categories'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:account_categories,id',
            'code' => 'required|string|unique:accounts,code,' . $account->id,
            'name' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $account->update($validated);

        return redirect()->route('admin.accounting.accounts.index')
            ->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('admin.accounting.accounts.index')
            ->with('success', 'Account deleted successfully.');
    }

    public function updateBalance(Account $account)
    {
        $account->updateBalance();
        return back()->with('success', 'Account balance updated successfully.');
    }
}
