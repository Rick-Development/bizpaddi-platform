<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Accounting\Models\Service;
use Modules\Accounting\Models\ServiceCategory;

class ServiceController extends ApiController
{
    /**
     * GET /api/v1/team/services
     * List all available services.
     */
    public function index(Request $request)
    {
        if (!$request->user()->isProjectManager()) {
            return $this->sendError('You do not have permission to view services.', 403);
        }

        $services = Service::with('category')->where('is_active', true)->get();

        return $this->sendResponse($services, 'Services retrieved successfully.');
    }

    /**
     * GET /api/v1/team/services/categories
     * List all service categories.
     */
    public function categories(Request $request)
    {
        if (!$request->user()->isProjectManager()) {
            return $this->sendError('You do not have permission to view service categories.', 403);
        }

        $categories = ServiceCategory::with('services')->where('is_active', true)->get();

        return $this->sendResponse($categories, 'Service categories retrieved successfully.');
    }
}
