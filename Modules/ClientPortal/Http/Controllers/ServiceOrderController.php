<?php

namespace Modules\ClientPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Service;
use Modules\ClientPortal\Models\ServiceOrder;

class ServiceOrderController extends Controller
{
    public function index()
    {
        $client = auth()->guard('client')->user();
        $orders = $client->serviceOrders()->with('service')->latest()->paginate(10);

        return view('clientportal::orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        // Typically triggered from Service Catalog Show page, so minimal logic here unless direct access needed
        return redirect()->route('client.services.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'details' => 'nullable|string',
            'features' => 'nullable|array',
            'feature_instructions' => 'nullable|string', 
        ]);

        $client = auth()->guard('client')->user();
        $service = Service::findOrFail($request->service_id);

        // Prepare details
        $details = [
            'requirements' => $request->details,
            'instructions' => $request->feature_instructions,
        ];

        // Capture feature selections snapshot
        $featureSelections = $request->features ?? [];

        $order = $client->serviceOrders()->create([
            'service_id' => $service->id,
            'status' => 'pending',
            'details' => $details,
            'feature_selections' => $featureSelections,
        ]);

        return redirect()->route('client.orders.show', $order)
            ->with('success', 'Order placed successfully! We will review it shortly.');
    }

    public function show($id)
    {
        $client = auth()->guard('client')->user();
        $order = $client->serviceOrders()->with(['service', 'quote', 'invoice', 'client'])->findOrFail($id);

        return view('clientportal::orders.show', compact('order'));
    }
}
