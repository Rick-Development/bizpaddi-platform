<?php

namespace Modules\ClientPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Contracts\Models\Contract;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::where('party_type', 'client')
            ->where('party_id', auth('client')->id())
            ->latest()
            ->paginate(15);
            
        return view('clientportal::contracts.index', compact('contracts'));
    }

    public function show($id)
    {
        $contract = Contract::where('party_type', 'client')
            ->where('party_id', auth('client')->id())
            ->where('id', $id)
            ->firstOrFail();

        // Check if contract is linked to a service order owned by client (extra security/logic if needed)
        // For now, matching party_id (client id) is sufficient if party_type is client.
        
        // OR if the contract was created via Service Order conversion, party_id might be set to Client ID context.
        // In ContractController::store (Admin), we pre-filled party details but didn't explicitly set party_id/party_type to linked user model ID if just text. 
        // Admin ContractController store: 'party_id' => 'nullable|integer'. 
        // If I pre-filled it, good. If not, I rely on 'party_email' matching client email?
        
        // Let's check Admin ContractController again.
        // It pre-filled party lines but party_id was not explicitly set in my pre-fill logic in create.blade.php.
        // This is a potential issue.
        // I should update ContractController (Admin) to ensure party_id is set when creating from Order if possible.
        // OR rely on ServiceOrder link:
        // $order->contract_id = $contract->id.
        // So I can find contract via ServiceOrder.
        
        // But for direct access (route('client.contracts.show')), I need validation.
        // I will use a simple check: if the contract is linked to any of the client's orders.
        
        $linkedOrder = \Modules\ClientPortal\Models\ServiceOrder::where('client_id', auth('client')->id())
            ->where('contract_id', $id)
            ->first();
            
        if (!$linkedOrder && $contract->party_email !== auth('client')->user()->email) {
             abort(403);
        }

        // We can just reuse the admin PDF generation or View.
        // Client probably just needs PDF.
        // Let's return PDF download or Stream.
        
        return $this->downloadPDF($contract);
    }
    
    public function downloadPDF(Contract $contract)
    {
        $contract->load(['currency', 'attachments']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin::contracts.pdf', compact('contract'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('Contract-' . $contract->contract_number . '.pdf');
    }
}
