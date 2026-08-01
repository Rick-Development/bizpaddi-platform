<?php

namespace Modules\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Marketing\Models\Campaign;

class CampaignController extends ApiController
{
    public function index(Request $request)
    {
        $campaigns = Campaign::latest()->paginate(20);
        return $this->sendResponse($campaigns, 'Campaigns retrieved successfully.');
    }

    public function show($id)
    {
        $campaign = Campaign::find($id);
        if (!$campaign) return $this->sendError('Campaign not found.', 404);
        return $this->sendResponse($campaign, 'Campaign details retrieved successfully.');
    }

    public function store(Request $request)
    {
        $campaign = Campaign::create($request->all());
        return $this->sendResponse($campaign, 'Campaign created successfully.', 201);
    }

    public function update(Request $request, $id)
    {
        $campaign = Campaign::find($id);
        if (!$campaign) return $this->sendError('Campaign not found.', 404);
        $campaign->update($request->all());
        return $this->sendResponse($campaign, 'Campaign updated successfully.');
    }

    public function destroy($id)
    {
        $campaign = Campaign::find($id);
        if (!$campaign) return $this->sendError('Campaign not found.', 404);
        $campaign->delete();
        return $this->sendResponse([], 'Campaign deleted successfully.');
    }
}
