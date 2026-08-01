@extends('admin::layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('admin.accounting.invoices.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create New Invoice</h1>
            <p class="mt-1 text-sm text-gray-600">Create and send invoices to your clients</p>
        </div>
    </div>
</div>

    <form action="{{ route('admin.accounting.invoices.store') }}" method="POST"
      x-data="invoiceForm({{ Js::from($services) }}, {{ Js::from($clients) }}, {{ Js::from($currencies) }}, {{ Js::from($featurePrices ?? []) }}, {{ Js::from($order ?? null) }}, {{ Js::from($serviceCategories) }}, {{ Js::from($defaultNotes) }}, {{ Js::from($defaultTerms) }})"
      @submit.prevent="submitForm">
    @csrf
    @if(isset($order))
        <input type="hidden" name="order_id" value="{{ $order->id }}">
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Invoice Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Alert for Linked Order -->
            @if(isset($order))
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Creating invoice from <strong>Order #{{ $order->id }}</strong>. Details have been pre-filled.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Client & Invoice Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Client & Invoice Details</h3>
                
                <!-- Existing Client Selector -->
                <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <label for="client_id" class="block text-sm font-medium text-blue-900 mb-2">Select Existing Client (Optional)</label>
                    <div class="flex gap-2">
                        <select name="client_id" id="client_id" x-model="selectedClientId" @change="fillClient()"
                                class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                            <option value="">-- Choose from Client List --</option>
                            <template x-for="client in clients" :key="client.id">
                                <option :value="client.id" x-text="client.company_name ? client.company_name + ' (' + client.name + ')' : client.name"></option>
                            </template>
                        </select>
                        <a href="{{ route('admin.clients.create') }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-brand-primary hover:bg-brand-primary/90">
                            New
                        </a>
                    </div>
                    <p class="mt-1 text-xs text-blue-700">Selecting a client will auto-fill the details below.</p>
                </div>

                <!-- Currency Selector -->
                <div class="mb-6">
                    <label for="currency_id" class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <select name="currency_id" id="currency_id" x-model="selectedCurrencyId" @change="updateCurrencySymbol()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <template x-for="currency in currencies" :key="currency.id">
                            <option :value="currency.id" x-text="currency.code + ' (' + currency.symbol + ')'" :selected="currency.is_default"></option>
                        </template>
                    </select>
                </div>

                <!-- Service Category Selector -->
                <div class="mb-4">
                    <label for="service_category_id" class="block text-sm font-medium text-gray-700 mb-1">Service Category</label>
                    <select name="service_category_id" id="service_category_id" x-model="selectedServiceCategoryId" @change="updateServicesByCategory()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">-- Select Service Category --</option>
                        <template x-for="category in serviceCategories" :key="category.id">
                            <option :value="category.id" x-text="category.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Service Selector -->
                <div class="mb-6">
                    <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                    <div class="flex gap-2">
                        <select name="service_id" id="service_id" x-model="selectedServiceId"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent"
                                :disabled="!selectedServiceCategoryId">
                            <option value="">-- Select Service --</option>
                            <template x-for="service in filteredServices" :key="service.id">
                                <option :value="service.id" x-text="service.name"></option>
                            </template>
                        </select>
                        <button type="button" @click="addServiceItem()" :disabled="!selectedServiceId" 
                                class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed">
                            Add to Items
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Select the main service for this project. Use "Add to Items" button if you want to include it as a line item.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Client Name -->
                    <div>
                        <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">Client Name *</label>
                        <input type="text" name="client_name" id="client_name" x-model="clientName" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                        @error('client_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Client Email -->
                    <div>
                        <label for="client_email" class="block text-sm font-medium text-gray-700 mb-1">Client Email *</label>
                        <input type="email" name="client_email" id="client_email" x-model="clientEmail" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        @error('client_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Client Phone -->
                    <div>
                        <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="client_phone" id="client_phone" x-model="clientPhone"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                        @error('client_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Issue Date -->
                    <div>
                        <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-1">Issue Date *</label>
                        <input type="date" name="issue_date" id="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                        @error('issue_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date *</label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        @error('due_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="client_address" class="block text-sm font-medium text-gray-700 mb-1">Client Address</label>
                        <textarea name="client_address" id="client_address" x-model="clientAddress" rows="2"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent"></textarea>
                        @error('client_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Line Items -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Items</h3>
                    <button type="button" @click="addItem()" class="text-sm text-brand-primary hover:text-brand-primary/80 font-medium flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Item
                    </button>
                </div>

                <div class="space-y-6">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-4">
                            <div class="grid grid-cols-12 gap-4 items-start">
                                <!-- Service Selection -->
                                <div class="col-span-12 md:col-span-4">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Service/Product</label>
                                    <select :name="`items[${index}][service_id]`" x-model="item.service_id" @change="updateService(index)"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                        <option value="">Custom Item</option>
                                        <template x-for="service in services" :key="service.id">
                                            <option :value="service.id" x-text="service.name"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="col-span-12 md:col-span-8">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Title</label>
                                    <input type="text" :name="`items[${index}][title]`" x-model="item.title" placeholder="Item Title" required
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                </div>
                            </div>

                            <!-- Description (Summernote) -->
                            <div class="col-span-12" x-init="initSummernote($el, index)">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Description</label>
                                <textarea :name="`items[${index}][description]`" class="summernote-editor" x-model="item.description"></textarea>
                            </div>

                            <div class="grid grid-cols-12 gap-4 items-center">
                                <!-- Quantity -->
                                <div class="col-span-4 md:col-span-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Qty</label>
                                    <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" min="0.01" step="0.01" required
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                </div>

                                <!-- Price -->
                                <div class="col-span-4 md:col-span-4">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Price</label>
                                    <input type="number" :name="`items[${index}][unit_price]`" x-model="item.unit_price" min="0" step="0.01" required
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                                </div>

                                <!-- Total -->
                                <div class="col-span-3 md:col-span-4 pt-5">
                                    <div class="text-right">
                                        <span class="text-xs text-gray-500 block">Subtotal</span>
                                        <span class="text-sm font-bold text-gray-900" x-text="formatMoney(item.quantity * item.unit_price)"></span>
                                    </div>
                                </div>

                                <!-- Delete -->
                                <div class="col-span-1 pt-5 text-right">
                                    <button type="button" @click="removeItem(index)" class="text-gray-400 hover:text-red-500 transition-colors" :disabled="items.length === 1">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Notes & Terms</h3>
                <div class="space-y-4">
                    <div x-init="initSummernoteSimple($el, 'notes')">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" id="notes" rows="3" x-model="notes" class="summernote-editor-simple w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Additional notes for the client...">{{ $defaultNotes }}</textarea>
                    </div>
                    <div x-init="initSummernoteSimple($el, 'terms_conditions')">
                        <label for="terms_conditions" class="block text-sm font-medium text-gray-700 mb-1">Terms & Conditions</label>
                        <textarea name="terms_conditions" id="terms_conditions" rows="3" x-model="terms_conditions" class="summernote-editor-simple w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent" placeholder="Payment terms, validity, etc...">{{ $defaultTerms }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Summary -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Summary</h3>
                
                <dl class="space-y-3">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">Subtotal</dt>
                        <dd class="text-sm font-medium text-gray-900" x-text="formatMoney(subtotal)"></dd>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600 flex items-center">
                            Tax
                            <input type="number" name="tax_rate" x-model="taxRate" class="w-16 ml-2 px-2 py-1 text-xs border border-gray-300 rounded" placeholder="%" min="0" max="100">
                            <span class="ml-1 text-xs">%</span>
                        </dt>
                        <dd class="text-sm font-medium text-gray-900" x-text="formatMoney(taxAmount)"></dd>
                    </div>

                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600 flex items-center">
                            Discount
                            <input type="number" name="discount_amount" x-model="discountAmount" class="w-20 ml-2 px-2 py-1 text-xs border border-gray-300 rounded" :placeholder="currencySymbol" min="0">
                        </dt>
                        <dd class="text-sm font-medium text-red-600" x-text="'-' + formatMoney(discountAmount)"></dd>
                    </div>

                    <div class="border-t border-gray-200 pt-3 flex items-center justify-between">
                        <dt class="text-base font-bold text-gray-900">Total</dt>
                        <dd class="text-lg font-bold text-brand-primary" x-text="formatMoney(total)"></dd>
                    </div>
                </dl>

                <div class="mt-6 space-y-3">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                        Create Invoice
                    </button>
                    <a href="{{ route('admin.accounting.invoices.index') }}" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function invoiceForm(servicesData, clientsData, currenciesData, featurePrices = [], order = null, serviceCategories = [], defaultNotes = '', defaultTerms = '') {
    return {
        items: [{ service_id: '', title: '', description: '', quantity: 1, unit_price: 0 }],
        services: servicesData,
        clients: clientsData,
        currencies: currenciesData,
        featurePrices: featurePrices,
        order: order,
        serviceCategories: serviceCategories,
        taxRate: 0,
        discountAmount: 0,
        selectedClientId: '',
        selectedCurrencyId: '',
        selectedServiceCategoryId: '',
        selectedServiceId: '',
        currencySymbol: '$', // Default
        clientName: '',
        clientEmail: '',
        clientPhone: '',
        clientAddress: '',
        notes: defaultNotes,
        terms_conditions: defaultTerms,

        init() {
            // Set default currency
            const defaultCurrency = this.currencies.find(c => c.is_default);
            if (defaultCurrency) {
                this.selectedCurrencyId = defaultCurrency.id;
                this.currencySymbol = defaultCurrency.symbol;
            } else if (this.currencies.length > 0) {
                 this.selectedCurrencyId = this.currencies[0].id;
                 this.currencySymbol = this.currencies[0].symbol;
            }

            if (this.order) {
                this.prefillFromOrder();
            }
        },

        get filteredServices() {
            if (!this.selectedServiceCategoryId) return [];
            return this.services.filter(s => s.category_id == this.selectedServiceCategoryId);
        },

        updateServicesByCategory() {
            this.selectedServiceId = '';
        },

        updateCurrencySymbol() {
            const currency = this.currencies.find(c => c.id == this.selectedCurrencyId);
            if (currency) {
                this.currencySymbol = currency.symbol;
            }
        },

        fillClient() {
            if (this.selectedClientId) {
                const client = this.clients.find(c => c.id == this.selectedClientId);
                if (client) {
                    this.clientName = client.name;
                    this.clientEmail = client.email;
                    this.clientPhone = client.phone || '';
                    
                    let addressParts = [client.address, client.city, client.state, client.zip, client.country];
                    this.clientAddress = addressParts.filter(part => part).join(', ');
                }
            }
        },

        addItem() {
            this.items.push({ service_id: '', title: '', description: '', quantity: 1, unit_price: 0 });
        },

        addServiceItem() {
            if (this.selectedServiceId) {
                const service = this.services.find(s => s.id == this.selectedServiceId);
                if (service) {
                    if (this.items.length === 1 && this.items[0].title === '' && this.items[0].description === '' && this.items[0].unit_price === 0) {
                        this.items[0] = {
                            service_id: service.id,
                            title: service.name,
                            description: service.name,
                            quantity: 1,
                            unit_price: parseFloat(service.base_price)
                        };
                    } else {
                        this.items.push({
                            service_id: service.id,
                            title: service.name,
                            description: service.name,
                            quantity: 1,
                            unit_price: parseFloat(service.base_price)
                        });
                    }
                    this.selectedServiceId = '';
                }
            }
        },

        removeItem(index) {
            this.items.splice(index, 1);
        },

        updateService(index) {
            const serviceId = this.items[index].service_id;
            const service = this.services.find(s => s.id == serviceId);
            
            if (service) {
                this.items[index].title = service.name;
                this.items[index].description = service.name;
                this.items[index].unit_price = parseFloat(service.base_price);
                
                // Update Summernote content if initialized
                this.$nextTick(() => {
                    const editor = $(document).find(`.summernote-editor`).eq(index);
                    if (editor.length) {
                        editor.summernote('code', service.name);
                    }
                });
            }
        },

        initSummernote(el, index) {
            this.$nextTick(() => {
                const textarea = el.querySelector('.summernote-editor');
                $(textarea).summernote({
                    height: 150,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['view', ['fullscreen', 'codeview']]
                    ],
                    callbacks: {
                        onChange: (contents) => {
                            this.items[index].description = contents;
                        }
                    }
                });
                
                // Set initial content if exists
                if (this.items[index].description) {
                    $(textarea).summernote('code', this.items[index].description);
                }
            });
        },

        initSummernoteSimple(el, field) {
            this.$nextTick(() => {
                const textarea = el.querySelector('.summernote-editor-simple');
                $(textarea).summernote({
                    height: 150,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['view', ['fullscreen', 'codeview']]
                    ],
                    callbacks: {
                        onChange: (contents) => {
                            this[field] = contents;
                        }
                    }
                });
                
                // Set initial content from the data property
                if (this[field]) {
                    $(textarea).summernote('code', this[field]);
                }
            });
        },

        prefillFromOrder() {
            if (this.items.length === 1 && this.items[0].title === '' && this.items[0].description === '' && this.items[0].unit_price === 0) {
                this.items = [];
            }

            if (this.order.client_id) {
                this.selectedClientId = this.order.client_id;
                this.fillClient();
            }

            if (this.order.service) {
                this.selectedServiceCategoryId = this.order.service.category_id;
                this.selectedServiceId = this.order.service_id;
                
                this.items.push({
                    service_id: this.order.service_id,
                    title: this.order.service.name,
                    description: this.order.service.name,
                    quantity: 1,
                    unit_price: parseFloat(this.order.service.base_price)
                });
            }

            if (this.order.feature_selections && this.order.feature_selections.length > 0) {
                 const featureIds = Array.isArray(this.order.feature_selections) 
                    ? this.order.feature_selections 
                    : JSON.parse(this.order.feature_selections);

                featureIds.forEach(fId => {
                    const feature = this.featurePrices.find(f => f.id == fId);
                    if (feature) {
                        this.items.push({
                            service_id: feature.service_id,
                            title: feature.feature_name,
                            description: feature.feature_name,
                            quantity: 1,
                            unit_price: parseFloat(feature.price)
                        });
                    }
                });
            }

            if (this.order.details && this.order.details.requirements) {
                this.notes = "Client Requirements:\n" + this.order.details.requirements;
            }
        },

        get subtotal() {
            return this.items.reduce((sum, item) => {
                return sum + (parseFloat(item.quantity || 0) * parseFloat(item.unit_price || 0));
            }, 0);
        },

        get taxAmount() {
            return (this.subtotal * (parseFloat(this.taxRate || 0) / 100));
        },

        get total() {
            return Math.max(0, this.subtotal + this.taxAmount - parseFloat(this.discountAmount || 0));
        },

        formatMoney(amount) {
            return this.currencySymbol + parseFloat(amount).toFixed(2);
        },
        
        submitForm(e) {
            e.target.submit();
        }
    }
}
</script>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame { border: 1px solid #d1d5db !important; border-radius: 0.5rem !important; overflow: hidden; background: white; }
    .note-toolbar { background: #f9fafb !important; border-bottom: 1px solid #d1d5db !important; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
@endpush
@endsection
