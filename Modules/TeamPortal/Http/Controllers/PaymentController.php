<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\SalaryDisbursement;

class PaymentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $payments = SalaryDisbursement::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('teamportal::payments.index', compact('payments'));
    }

    public function show($id)
    {
        $user = auth()->user();
        $payment = SalaryDisbursement::where('user_id', $user->id)->findOrFail($id);
        
        return view('teamportal::payments.show', compact('payment'));
    }
}
