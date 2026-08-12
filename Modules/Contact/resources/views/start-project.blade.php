<x-home::layouts.master>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-brand-secondary via-brand-primary to-purple-900 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-extrabold text-white sm:text-6xl md:text-7xl">
                    {{ __('Start Your Project') }}
                </h1>
                <p class="mt-6 max-w-3xl mx-auto text-xl text-gray-100">
                    {{ __('Tell us about your project, and our team will get back to you with a detailed proposal.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Multi-Step Form Section -->
    <div class="py-20 bg-gray-50" 
         x-data="{ 
            step: 1, 
            loading: false,
            success: false,
            errors: {},
            errorMessage: null,
            formData: {
                name: '',
                email: '',
                phone: '',
                company: '',
                project_type: '',
                budget: '',
                timeline: '',
                description: '',
                utm_source: new URLSearchParams(window.location.search).get('utm_source') || '',
                utm_medium: new URLSearchParams(window.location.search).get('utm_medium') || '',
                utm_campaign: new URLSearchParams(window.location.search).get('utm_campaign') || '',
                utm_term: new URLSearchParams(window.location.search).get('utm_term') || '',
                utm_content: new URLSearchParams(window.location.search).get('utm_content') || ''
            },
            errors: {},
            validateStep() {
                this.errors = {};
                if (this.step === 1) {
                    if (!this.formData.name) this.errors.name = 'Name is required';
                    if (!this.formData.email) {
                        this.errors.email = 'Email is required';
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.formData.email)) {
                        this.errors.email = 'Valid email is required';
                    }
                    if (Object.keys(this.errors).length === 0) this.step = 2;
                } else if (this.step === 2) {
                    if (!this.formData.project_type) this.errors.project_type = 'Please select a project type';
                    if (!this.formData.budget) this.errors.budget = 'Please select a budget range';
                    if (!this.formData.timeline) this.errors.timeline = 'Please select a timeline';
                    if (Object.keys(this.errors).length === 0) this.step = 3;
                }
            },
            async submitForm() {
                this.loading = true;
                this.errors = {};
                try {
                    const response = await fetch('{{ route('lead.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.formData)
                    });
                    
                    const data = await response.json();
                    if (response.ok) {
                        this.success = true;
                        this.step = 4;
                    } else {
                        if (data.errors) {
                            this.errors = data.errors;
                        } else {
                            this.errorMessage = 'An error occurred. Please try again.';
                        }
                    }
                } catch (error) {
                    this.errorMessage = 'A network error occurred. Please try again.';
                }
                this.loading = false;
            }
         }">
         
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 md:p-12 shadow-2xl relative overflow-hidden">
                
                <!-- Progress Bar -->
                <div class="mb-10 relative" x-show="step < 4">
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                        <div :style="`width: ${((step - 1) / 2) * 100}%`" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-brand-primary transition-all duration-500"></div>
                    </div>
                    <div class="flex justify-between text-xs font-bold text-gray-500 uppercase">
                        <span :class="{ 'text-brand-primary': step >= 1 }">1. Details</span>
                        <span :class="{ 'text-brand-primary': step >= 2 }">2. Project</span>
                        <span :class="{ 'text-brand-primary': step >= 3 }">3. Review</span>
                    </div>
                </div>

                <!-- Step 1: Basic Info -->
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">{{ __('Your Details') }}</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Full Name') }} <span class="text-red-500">*</span></label>
                            <input type="text" x-model="formData.name" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all" :class="{'border-red-500': errors.name}">
                            <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-xs mt-1"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Email Address') }} <span class="text-red-500">*</span></label>
                            <input type="email" x-model="formData.email" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all" :class="{'border-red-500': errors.email}">
                            <p x-show="errors.email" x-text="errors.email" class="text-red-500 text-xs mt-1"></p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Phone Number') }}</label>
                                <input type="tel" x-model="formData.phone" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Company (Optional)') }}</label>
                                <input type="text" x-model="formData.company" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all">
                            </div>
                        </div>
                        <div class="pt-4 flex justify-end">
                            <button @click="validateStep()" class="bg-gradient-to-r from-brand-primary to-purple-600 text-white font-bold py-3 px-8 rounded-xl hover:from-brand-primary hover:to-purple-700 shadow-lg transform hover:-translate-y-1 transition-all">
                                {{ __('Next Step') }} &rarr;
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Project Details -->
                <div x-show="step === 2" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">{{ __('Project Requirements') }}</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('What type of project is this?') }} <span class="text-red-500">*</span></label>
                            <select x-model="formData.project_type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all" :class="{'border-red-500': errors.project_type}">
                                <option value="">Select Project Type</option>
                                @foreach($projectTypes as $type)
                                    <option value="{{ $type->name }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <p x-show="errors.project_type" x-text="errors.project_type" class="text-red-500 text-xs mt-1"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Estimated Budget') }} <span class="text-red-500">*</span></label>
                            <select x-model="formData.budget" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all" :class="{'border-red-500': errors.budget}">
                                <option value="">Select Budget Range</option>
                                @foreach($budgets as $budget)
                                    <option value="{{ $budget->name }}">{{ $budget->name }}</option>
                                @endforeach
                            </select>
                            <p x-show="errors.budget" x-text="errors.budget" class="text-red-500 text-xs mt-1"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Expected Timeline') }} <span class="text-red-500">*</span></label>
                            <select x-model="formData.timeline" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all" :class="{'border-red-500': errors.timeline}">
                                <option value="">Select Timeline</option>
                                @foreach($timelines as $timeline)
                                    <option value="{{ $timeline->name }}">{{ $timeline->name }}</option>
                                @endforeach
                            </select>
                            <p x-show="errors.timeline" x-text="errors.timeline" class="text-red-500 text-xs mt-1"></p>
                        </div>
                        <div class="pt-4 flex justify-between">
                            <button @click="step = 1" class="text-gray-500 hover:text-gray-900 font-bold py-3 px-6 transition-colors">
                                &larr; {{ __('Back') }}
                            </button>
                            <button @click="validateStep()" class="bg-gradient-to-r from-brand-primary to-purple-600 text-white font-bold py-3 px-8 rounded-xl hover:from-brand-primary hover:to-purple-700 shadow-lg transform hover:-translate-y-1 transition-all">
                                {{ __('Next Step') }} &rarr;
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Description -->
                <div x-show="step === 3" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">{{ __('Project Description') }}</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Tell us more about your project goals (Optional)') }}</label>
                            <textarea x-model="formData.description" rows="6" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all" placeholder="Any specific requirements, technologies, or competitors to look at?"></textarea>
                        </div>
                        <div class="pt-4 flex justify-between items-center">
                            <button @click="step = 2" class="text-gray-500 hover:text-gray-900 font-bold py-3 px-6 transition-colors" :disabled="loading">
                                &larr; {{ __('Back') }}
                            </button>
                            <button @click="submitForm()" :disabled="loading" class="bg-gradient-to-r from-brand-primary to-purple-600 text-white font-bold py-3 px-8 rounded-xl hover:from-brand-primary hover:to-purple-700 shadow-lg transform hover:-translate-y-1 transition-all flex items-center gap-2" :class="{'opacity-75 cursor-not-allowed': loading}">
                                <span x-show="!loading">{{ __('Submit Request') }}</span>
                                <span x-show="loading">{{ __('Submitting...') }}</span>
                                <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Success -->
                <div x-show="step === 4" style="display: none;" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="text-center py-8">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Request Received!') }}</h2>
                    <p class="text-gray-600 text-lg mb-8">
                        {{ __('Thank you for your interest, ') }}<span x-text="formData.name" class="font-semibold text-gray-900"></span>.
                        <br>{{ __('Our team will review your project details and get back to you within 24 hours.') }}
                    </p>
                    <a href="{{ url('/') }}" class="inline-block bg-gray-900 text-white font-bold py-3 px-8 rounded-xl hover:bg-gray-800 transition-colors">
                        {{ __('Return Home') }}
                    </a>
                </div>
                <!-- Error Modal -->
                <div x-show="errorMessage" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div @click.away="errorMessage = null" class="bg-white rounded-xl shadow-xl max-w-sm w-full mx-4 overflow-hidden transform transition-all" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-center mb-4 text-red-500">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-center text-gray-900 mb-2">Submission Error</h3>
                            <p class="text-center text-gray-600 text-sm" x-text="errorMessage"></p>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button @click="errorMessage = null" type="button" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                OK
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-gray-50 py-20 border-t border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900">{{ __('Frequently Asked Questions') }}</h2>
                <p class="mt-4 text-xl text-gray-600">Got questions? We've got answers.</p>
            </div>
            
            <div class="space-y-6" x-data="{ active: null }">
                <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow">
                    <button class="w-full px-6 py-5 text-left font-bold text-gray-900 flex justify-between items-center focus:outline-none" @click="active = active === 1 ? null : 1">
                        <span>How much does a typical project cost?</span>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="px-6 pb-5 text-gray-600 border-t border-gray-100" x-show="active === 1" x-collapse x-cloak>
                        <p class="pt-4">Project costs vary widely depending on scope, complexity, and timeline. After our initial free consultation, we provide a detailed proposal with transparent pricing tailored to your specific needs.</p>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow">
                    <button class="w-full px-6 py-5 text-left font-bold text-gray-900 flex justify-between items-center focus:outline-none" @click="active = active === 2 ? null : 2">
                        <span>How long does it take to complete a project?</span>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="px-6 pb-5 text-gray-600 border-t border-gray-100" x-show="active === 2" x-collapse x-cloak>
                        <p class="pt-4">Timelines depend entirely on the project scope. We emphasize agile methodologies, meaning we deliver working software in sprints so you see progress early and often.</p>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow">
                    <button class="w-full px-6 py-5 text-left font-bold text-gray-900 flex justify-between items-center focus:outline-none" @click="active = active === 4 ? null : 4">
                        <span>What is your development process like?</span>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300" :class="active === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="px-6 pb-5 text-gray-600 border-t border-gray-100" x-show="active === 4" x-collapse x-cloak>
                        <p class="pt-4">We follow a transparent, agile process: Discovery & Strategy -> UI/UX Design -> Development -> QA & Testing -> Launch. You'll have regular check-ins every step of the way.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-home::layouts.master>
