<?php

namespace Modules\ClientPortal\Http\Controllers\Api;

use Modules\Accounting\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends ApiController
{
    public function index()
    {
        $categories = ServiceCategory::with(['services' => function ($query) {
            $query->active()->with('currency');
        }])->orderBy('sort_order')->get();

        return $this->sendResponse($categories, 'Service categories retrieved successfully.');
    }

    public function show($id)
    {
        $category = ServiceCategory::with(['services' => function ($query) {
            $query->active()->with('currency');
        }])->find($id);

        if (!$category) {
            return $this->sendError('Service category not found.');
        }

        return $this->sendResponse($category, 'Service category retrieved successfully.');
    }
}
