<?php

namespace Modules\Admin\Http\Controllers\Api\Contracts;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Api\ApiController;
use Modules\Contracts\Models\ContractTemplate;

class ContractTemplateController extends ApiController
{
    public function index(Request $request)
    {
        $templates = ContractTemplate::latest()->paginate(20);
        return $this->sendResponse($templates, 'Contract templates retrieved successfully.');
    }

    public function show($id)
    {
        $template = ContractTemplate::find($id);
        if (!$template) return $this->sendError('Contract template not found.', 404);
        return $this->sendResponse($template, 'Contract template details retrieved successfully.');
    }

    public function store(Request $request)
    {
        $template = ContractTemplate::create($request->all());
        return $this->sendResponse($template, 'Contract template created successfully.', 201);
    }

    public function update(Request $request, $id)
    {
        $template = ContractTemplate::find($id);
        if (!$template) return $this->sendError('Contract template not found.', 404);
        $template->update($request->all());
        return $this->sendResponse($template, 'Contract template updated successfully.');
    }

    public function destroy($id)
    {
        $template = ContractTemplate::find($id);
        if (!$template) return $this->sendError('Contract template not found.', 404);
        $template->delete();
        return $this->sendResponse([], 'Contract template deleted successfully.');
    }
}
