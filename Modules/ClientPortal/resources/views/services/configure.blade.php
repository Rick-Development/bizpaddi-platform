@extends('clientportal::layouts.app')

@section('title', 'Order ' . $service->name)

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('client.services.show', $service->id) }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Configure {{ $service->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Select your preferences and submit your order request</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Configuration Form -->
    <div class="lg:col-span-2">
        <form action="{{ route('client.orders.store') }}" method="POST" id="order-form">
            @csrf
            <input type="hidden" name="service_id" value="{{ $service->id }}">

            <div class="space-y-6">
                <!-- Additional Details -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-hidden">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Project Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="details" class="block text-sm font-medium text-gray-700">Specific Requirements</label>
                            <p class="text-xs text-gray-500 mb-2">Describe what you need in detail. The more info you provide, the better we can quote you.</p>
                            <input type="hidden" name="details">
                            <div id="editor-container"></div>
                        </div>
                    </div>
                </div>

                <!-- Features Selection -->
                @if($features->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-hidden">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Add-on Features</h3>
                    <p class="text-sm text-gray-500 mb-6">Select additional features to enhance your service package.</p>
                    
                    <div class="space-y-4">
                        @foreach($features as $feature)
                        <label for="feature_{{ $feature->id }}" class="relative flex items-start p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                            <div class="flex items-center h-5">
                                <input id="feature_{{ $feature->id }}" name="features[]" value="{{ $feature->id }}" type="checkbox" data-price="{{ $feature->price }}" class="focus:ring-brand-primary h-5 w-5 text-brand-primary border-gray-300 rounded feature-checkbox">
                            </div>
                            <div class="ml-3 flex-1">
                                <span class="block font-medium text-gray-700 select-none">{{ $feature->feature_name }}</span>
                                <p class="text-gray-500 text-sm select-none">{{ $feature->description }}</p>
                            </div>
                            <div class="ml-auto pl-4 font-semibold text-gray-900">
                                +{{ $currency->symbol }}{{ number_format($feature->price, 2) }}
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <label for="feature_instructions" class="block text-sm font-medium text-gray-700">Instructions for selected features (Optional)</label>
                        <textarea name="feature_instructions" id="feature_instructions" rows="2" class="mt-1 shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-xl p-3" placeholder="Any specific preferences for the selected add-ons?"></textarea>
                    </div>
                </div>
                @endif
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-xl text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary shadow-brand-primary/30 transition-all hover:-translate-y-1">
                    Submit Order Request
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-lg p-6 text-gray-900 sticky top-6 border border-gray-100">
            <h3 class="text-lg font-bold mb-6">Order Summary</h3>
            
            <div class="space-y-4 mb-6">
                <div class="flex justify-between items-start">
                    <span class="text-gray-600">{{ $service->name }}</span>
                    <span class="font-medium text-gray-900">{{ $currency->symbol }}{{ number_format($service->base_price, 2) }}</span>
                </div>
                
                <div id="selected-features-list" class="space-y-2 text-sm text-gray-500 pt-4 border-t border-gray-100 hidden">
                    <!-- Javascript will populate this -->
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-between items-center">
                <span class="text-lg font-medium">Estimated Total</span>
                <span class="text-2xl font-bold text-brand-primary" id="total-price">{{ $currency->symbol }}{{ number_format($service->base_price, 2) }}</span>
            </div>
            
            <p class="mt-6 text-xs text-gray-500 text-center">
                *Final price may vary based on specific requirements and scope assessment. We will send you a formal quote for approval.
            </p>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor { min-height: 150px; font-family: 'Outfit', sans-serif; font-size: 0.875rem; }
    .ql-container { border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; background: white; }
    .ql-toolbar { border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem; background: #f9fafb; border-color: #d1d5db !important; }
    .ql-container.ql-snow { border-color: #d1d5db !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quill Editor Setup
        if(document.getElementById('editor-container')) {
            var quill = new Quill('#editor-container', {
                theme: 'snow',
                placeholder: 'Describe what you need in detail...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'header': [2, 3, false] }],
                        ['clean']
                    ]
                }
            });

            var form = document.querySelector('form');
            form.onsubmit = function() {
                var content = document.querySelector('input[name=details]');
                content.value = quill.root.innerHTML;
            };
        }

        // Feature Price Calculation
        const basePrice = {{ $service->base_price }};
        const checkboxes = document.querySelectorAll('.feature-checkbox');
        const totalPriceEl = document.getElementById('total-price');
        const selectedFeaturesList = document.getElementById('selected-features-list');

        function updateSummary() {
            let total = basePrice;
            let featuresHtml = '';

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const price = parseFloat(cb.dataset.price);
                    const name = cb.closest('label').querySelector('.ml-3 span').innerText;
                    total += price;
                    
                    featuresHtml += `
                        <div class="flex justify-between">
                            <span>${name}</span>
                            <span>+{{ $currency->symbol }}${price.toFixed(2)}</span>
                        </div>
                    `;
                }
            });

            totalPriceEl.innerText = '{{ $currency->symbol }}' + total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            
            if (featuresHtml) {
                selectedFeaturesList.innerHTML = '<div class="text-xs uppercase tracking-wider font-semibold text-gray-500 mb-2">Add-ons</div>' + featuresHtml;
                selectedFeaturesList.classList.remove('hidden');
            } else {
                selectedFeaturesList.classList.add('hidden');
                selectedFeaturesList.innerHTML = '';
            }
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateSummary));
    });
</script>
@endpush
@endsection
