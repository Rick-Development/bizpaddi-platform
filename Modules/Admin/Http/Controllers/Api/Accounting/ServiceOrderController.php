<?php

namespace Modules\Admin\Http\Controllers\Api\Accounting;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Api\ApiController;
use Modules\Accounting\Models\Service;

class ServiceOrderController extends ApiController
{
    public function index()
    {
        $services = Service::latest()->paginate(20);
        return $this->sendResponse($services, 'Services retrieved successfully.');
    }

    public function show($id)
    {
        $service = Service::find($id);
        if (!$service) return $this->sendError('Service not found.', 404);
        return $this->sendResponse($service, 'Service details retrieved successfully.');
    }

    public function store(Request $request)
    {
        $service = Service::create($request->all());
        return $this->sendResponse($service, 'Service created successfully.', 201);
    }

    public function update(Request $request, $id)
    {
        $service = Service::find($id);
        if (!$service) return $this->sendError('Service not found.', 404);
        $service->update($request->all());
        return $this->sendResponse($service, 'Service updated successfully.');
    }

    public function destroy($id)
    {
        $service = Service::find($id);
        if (!$service) return $this->sendError('Service not found.', 404);
        $service->delete();
        return $this->sendResponse([], 'Service deleted successfully.');
    }
}
