<?php

namespace Modules\Home\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch active SaaS plans dynamically from DB
        $plans = \App\Models\SaasPlan::active()->get();
        $subscriptionPlans = $plans->where('billing_type', '!=', 'payg');
        $paygPlan = $plans->firstWhere('billing_type', 'payg');
        $featureRates = \App\Models\PlatformFeatureRate::where('is_active', true)->get()->keyBy('action_key');

        return view('home::index', compact(
            'plans',
            'subscriptionPlans',
            'paygPlan',
            'featureRates'
        ));
    }

    /**
     * Dedicated Features page.
     */
    public function features()
    {
        return view('home::features');
    }

    /**
     * Dedicated Pricing page.
     */
    public function pricing()
    {
        $plans = \App\Models\SaasPlan::active()->get();
        $subscriptionPlans = $plans->where('billing_type', '!=', 'payg');
        $paygPlan = $plans->firstWhere('billing_type', 'payg');
        $featureRates = \App\Models\PlatformFeatureRate::where('is_active', true)->get()->keyBy('action_key');
        return view('home::pricing', compact('plans', 'subscriptionPlans', 'paygPlan', 'featureRates'));
    }

    /**
     * Dedicated FAQ page.
     */
    public function faq()
    {
        return view('home::faq');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('home::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('home::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('home::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
