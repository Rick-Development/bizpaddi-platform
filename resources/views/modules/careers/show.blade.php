<x-home::layouts.master>
    <div class="bg-white py-16 sm:py-24">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Job Header -->
            <div class="text-center mb-16">
                <h2 class="text-base text-brand-primary font-semibold tracking-wide uppercase">{{ $job->department }}</h2>
                <h1 class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    {{ $job->title }}
                </h1>
                <p class="mt-4 text-xl text-gray-500">
                    {{ ucfirst(str_replace('-', ' ', $job->type)) }} &bull; {{ $job->location }}
                </p>
            </div>

            <!-- Job Details -->
            <div class="prose prose-blue prose-lg mx-auto text-gray-500 mb-16">
                <h3>About the Role</h3>
                <div class="whitespace-pre-wrap">{{ $job->description }}</div>

                @if($job->requirements)
                    <h3 class="mt-8">Requirements</h3>
                    <div class="whitespace-pre-wrap">{{ $job->requirements }}</div>
                @endif
            </div>

            <!-- Application Form -->
            <div class="bg-gray-50 rounded-2xl p-8 sm:p-12 shadow-sm border border-gray-100" id="apply">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Apply for this Position</h3>
                
                @if(session('success'))
                    <div class="rounded-md bg-green-50 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('careers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="job_id" value="{{ $job->id }}">

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" required class="py-3 px-4 block w-full shadow-sm focus:ring-brand-primary focus:border-brand-primary border-gray-300 rounded-md" value="{{ old('name') }}">
                            </div>
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-1">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email" required class="py-3 px-4 block w-full shadow-sm focus:ring-brand-primary focus:border-brand-primary border-gray-300 rounded-md" value="{{ old('email') }}">
                            </div>
                            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-1">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <div class="mt-1">
                                <input type="tel" name="phone" id="phone" class="py-3 px-4 block w-full shadow-sm focus:ring-brand-primary focus:border-brand-primary border-gray-300 rounded-md" value="{{ old('phone') }}">
                            </div>
                            @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="linkedin" class="block text-sm font-medium text-gray-700">LinkedIn Profile URL</label>
                            <div class="mt-1">
                                <input type="url" name="linkedin" id="linkedin" class="py-3 px-4 block w-full shadow-sm focus:ring-brand-primary focus:border-brand-primary border-gray-300 rounded-md" value="{{ old('linkedin') }}">
                            </div>
                            @error('linkedin') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="resume" class="block text-sm font-medium text-gray-700">Resume/CV (PDF, DOC, DOCX) *</label>
                            <div class="mt-1">
                                <input type="file" name="resume" id="resume" required accept=".pdf,.doc,.docx" class="py-3 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-brand-primary focus:border-brand-primary text-sm">
                            </div>
                            @error('resume') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="cover_letter" class="block text-sm font-medium text-gray-700">Cover Letter</label>
                            <div class="mt-1">
                                <textarea id="cover_letter" name="cover_letter" rows="4" class="py-3 px-4 block w-full shadow-sm focus:ring-brand-primary focus:border-brand-primary border border-gray-300 rounded-md">{{ old('cover_letter') }}</textarea>
                            </div>
                            @error('cover_letter') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-home::layouts.master>
