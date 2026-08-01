<?php

namespace Modules\ClientPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Service;
use Modules\Accounting\Models\ServiceCategory;
use Modules\Accounting\Models\FeaturePrice;

class ServiceCatalogController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::with(['services' => function ($query) {
            $query->active()->with('currency');
        }])->orderBy('sort_order')->get();

        return view('clientportal::services.index', compact('categories'));
    }

    public function show($id)
    {
        $service = Service::active()->with(['category', 'currency'])->findOrFail($id);
        
        $currency = $service->currency ?? \Modules\Accounting\Models\Currency::where('is_default', true)->first() 
            ?? new \Modules\Accounting\Models\Currency(['symbol' => '$', 'code' => 'USD']);

        return view('clientportal::services.show', compact('service', 'currency'));
    }

    public function configure($id)
    {
        $service = Service::active()->with(['category', 'currency'])->findOrFail($id);
        $features = FeaturePrice::active()->get();
        
        $currency = $service->currency ?? \Modules\Accounting\Models\Currency::where('is_default', true)->first() 
            ?? new \Modules\Accounting\Models\Currency(['symbol' => '$', 'code' => 'USD']);

        return view('clientportal::services.configure', compact('service', 'features', 'currency'));
    }
}
