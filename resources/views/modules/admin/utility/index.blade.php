@extends('admin::layouts.app')

@section('title', 'Team Utilities')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Team Utilities</h1>
        <p class="mt-2 text-sm text-gray-500">Subscribe your team to Airtime and Data plans.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Purchase Form -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <form action="{{ route('admin.team-utilities.purchase') }}" method="POST">
                    @csrf
                    
                    <!-- Team Member Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Select Team Member</label>
                        <select name="user_id" id="user_select" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all">
                            <option value="">Select a member...</option>
                            @foreach($teamMembers as $member)
                                <option value="{{ $member->id }}" 
                                    data-airtime="{{ $member->airtime_phone }}" 
                                    data-data="{{ $member->data_phone }}"
                                    data-phone="{{ $member->phone }}">
                                    {{ $member->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Service Type -->
                    <div class="mb-6" id="service_section" style="display: none;">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Service Type</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer" onclick="selectService('AIRTIME')">
                                <input type="radio" name="type" value="AIRTIME" id="type_airtime" class="peer sr-only">
                                <div id="div_airtime" class="p-4 rounded-xl border-2 border-gray-200 hover:border-brand-primary/50 transition-all text-center">
                                    <span class="block font-bold">Airtime</span>
                                </div>
                            </label>
                            <label class="cursor-pointer" onclick="selectService('DATA')">
                                <input type="radio" name="type" value="DATA" id="type_data" class="peer sr-only">
                                <div id="div_data" class="p-4 rounded-xl border-2 border-gray-200 hover:border-brand-primary/50 transition-all text-center">
                                    <span class="block font-bold">Data Bundle</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Phone Selection -->
                    <div class="mb-6" id="phone_section" style="display: none;">
                         <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                         <div class="flex gap-2">
                             <input type="text" name="phone_number" id="phone_input" class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Enter phone number" required>
                             <button type="button" id="use_saved_phone" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-200 transition-all whitespace-nowrap">
                                Use Saved
                             </button>
                         </div>
                         <p class="mt-1 text-xs text-gray-500" id="saved_phone_hint"></p>
                    </div>

                    <!-- Provider Selection -->
                    <div class="mb-6" id="provider_section" style="display: none;">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Select Provider</label>
                        <select name="service_category_id" id="provider_select" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                            <option value="">Select Provider...</option>
                        </select>
                    </div>

                    <!-- Data Bundle Selection -->
                    <div class="mb-6" id="bundle_section" style="display: none;">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Select Bundle</label>
                        <select name="bundle_code" id="bundle_select" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                            <option value="">Select Bundle...</option>
                        </select>
                        <input type="hidden" name="data_amount" id="data_amount">
                    </div>

                    <!-- Amount Input (Airtime) -->
                    <div class="mb-6" id="amount_section" style="display: none;">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Amount</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">₦</span>
                            <input type="number" name="amount" id="amount_input" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="0.00">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-brand-primary text-white rounded-xl font-bold text-lg hover:bg-brand-primary/90 transition-all shadow-lg shadow-brand-primary/30">
                        Process Subscription
                    </button>
                </form>
            </div>
        </div>

        <!-- Instructions / Info -->
        <div class="space-y-6">
            <div class="bg-blue-50 rounded-3xl p-8 border border-blue-100">
                <h3 class="text-lg font-bold text-blue-900 mb-4">How it works</h3>
                <ul class="space-y-3 text-sm text-blue-800">
                    <li class="flex items-start gap-2">
                        <span class="mt-1 block h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                        Select a team member to auto-load their saved utility numbers.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 block h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                        Choose between Airtime recharge or Data bundle subscription.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 block h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                        Funds are deducted directly from the corporate settlement account.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userSelect = document.getElementById('user_select');
        const serviceSection = document.getElementById('service_section');
        const phoneSection = document.getElementById('phone_section');
        const phoneInput = document.getElementById('phone_input');
        const useSavedPhoneBtn = document.getElementById('use_saved_phone');
        const savedPhoneHint = document.getElementById('saved_phone_hint');
        const providerSection = document.getElementById('provider_section');
        const providerSelect = document.getElementById('provider_select');
        const bundleSection = document.getElementById('bundle_section');
        const bundleSelect = document.getElementById('bundle_select');
        const amountSection = document.getElementById('amount_section');
        const amountInput = document.getElementById('amount_input');
        const dataAmountInput = document.getElementById('data_amount');

        let selectedUser = null;
        let selectedType = null;
        
        // Pass PHP variables to JS
        const services = @json($utilityServices);

        userSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (option.value) {
                selectedUser = {
                    id: option.value,
                    airtime: option.dataset.airtime,
                    data: option.dataset.data,
                    phone: option.dataset.phone
                };
                serviceSection.style.display = 'block';
                resetForm();
            } else {
                serviceSection.style.display = 'none';
                resetForm();
            }
        });

        document.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                selectedType = this.value;
                phoneSection.style.display = 'block';
                providerSection.style.display = 'block';
                updateSavedPhoneHint();
                loadProviders();
                
                if (selectedType === 'AIRTIME') {
                    amountSection.style.display = 'block';
                    bundleSection.style.display = 'none';
                } else {
                    amountSection.style.display = 'none';
                    // Bundle section will be shown after provider selection
                }
            });
        });

        useSavedPhoneBtn.addEventListener('click', function() {
            if (!selectedUser) return;
            let phoneToUse = '';
            if (selectedType === 'AIRTIME') phoneToUse = selectedUser.airtime || selectedUser.phone;
            if (selectedType === 'DATA') phoneToUse = selectedUser.data || selectedUser.phone;
            
            if (phoneToUse) {
                phoneInput.value = phoneToUse;
            } else {
                alert('No saved number found for this user/type.');
            }
        });

        function updateSavedPhoneHint() {
            if (!selectedUser || !selectedType) return;
            let hint = '';
            if (selectedType === 'AIRTIME') {
                hint = selectedUser.airtime ? `Saved Airtime: ${selectedUser.airtime}` : (selectedUser.phone ? `Main Phone: ${selectedUser.phone}` : 'No saved number');
            } else {
                hint = selectedUser.data ? `Saved Data: ${selectedUser.data}` : (selectedUser.phone ? `Main Phone: ${selectedUser.phone}` : 'No saved number');
            }
            savedPhoneHint.textContent = hint;
        }

        function loadProviders() {
            providerSelect.innerHTML = '<option value="">Loading...</option>';
            
            // Find the service ID for the selected type
            const service = Object.values(services).find(s => s.identifier === selectedType);
            
            if (service) {
                // Fetch providers via AJAX
                const baseUrl = "{{ route('admin.vas.categories', ['serviceId' => ':id']) }}";
                const url = baseUrl.replace(':id', service._id);
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        providerSelect.innerHTML = '<option value="">Select Provider...</option>';
                        if (data.data && Array.isArray(data.data)) {
                            data.data.forEach(provider => {
                                const option = document.createElement('option');
                                option.value = provider._id;
                                option.textContent = provider.name;
                                providerSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching providers:', error);
                        providerSelect.innerHTML = '<option value="">Error loading providers</option>';
                    });
            }
        }

        providerSelect.addEventListener('change', function() {
            if (selectedType === 'DATA' && this.value) {
                loadBundles(this.value);
            }
        });

        function loadBundles(categoryId) {
            bundleSection.style.display = 'block';
            bundleSelect.innerHTML = '<option value="">Loading...</option>';

                const baseUrl = "{{ route('admin.vas.products', ['categoryId' => ':id']) }}";
                const url = baseUrl.replace(':id', categoryId);

                fetch(url)
                    .then(response => response.json())
                .then(data => {
                    bundleSelect.innerHTML = '<option value="">Select Bundle...</option>';
                    if (data.data && Array.isArray(data.data)) {
                        data.data.forEach(bundle => {
                            const option = document.createElement('option');
                            option.value = bundle.bundleCode;
                            option.dataset.amount = bundle.amount;
                            option.textContent = `${bundle.validity} - ₦${bundle.amount}`;
                            bundleSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching bundles:', error);
                    bundleSelect.innerHTML = '<option value="">Error loading bundles</option>';
                    bundleSection.style.display = 'none';
                });
        }

        bundleSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (option.dataset.amount) {
                dataAmountInput.value = option.dataset.amount;
            }
        });

        function resetForm() {
            phoneSection.style.display = 'none';
            providerSection.style.display = 'none';
            bundleSection.style.display = 'none';
            amountSection.style.display = 'none';
            phoneInput.value = '';
            amountInput.value = '';
            providerSelect.innerHTML = '<option value="">Select Provider...</option>';
            document.querySelectorAll('input[name="type"]').forEach(r => r.checked = false);
            selectedType = null;
        }

        window.selectService = function(type) {
            // Reset styles
            ['airtime', 'data'].forEach(t => {
                const div = document.getElementById(`div_${t}`);
                div.classList.remove('border-brand-primary', 'bg-brand-primary', 'text-white');
                div.classList.add('border-gray-200');
            });

            // Apply active style
            const activeDiv = document.getElementById(`div_${type.toLowerCase()}`);
            activeDiv.classList.remove('border-gray-200');
            activeDiv.classList.add('border-brand-primary', 'bg-brand-primary', 'text-white');
        };

        // Initialize helper to reset styles on form reset
        const originalResetForm = resetForm;
        resetForm = function() {
            originalResetForm();
            ['airtime', 'data'].forEach(t => {
                const div = document.getElementById(`div_${t}`);
                if(div) {
                    div.classList.remove('border-brand-primary', 'bg-brand-primary', 'text-white');
                    div.classList.add('border-gray-200');
                }
            });
        }
    });
</script>
@endpush
@endsection
