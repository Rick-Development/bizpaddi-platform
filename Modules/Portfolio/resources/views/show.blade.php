<x-home::layouts.master>
    @section('title', strip_tags(html_entity_decode($project->title)))
    @section('description', Str::limit(strip_tags($project->description), 160))

    <!-- Project Header -->
    <div class="relative bg-gray-900 py-24 sm:py-32 overflow-hidden">
        @if($project->featured_image)
            <div class="absolute inset-0">
                <img src="{{ asset('storage/' . $project->featured_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover opacity-30 blur-sm">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-brand-primary to-purple-900 opacity-90"></div>
        @endif

        <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                @if($project->type)
                    <div class="mb-8">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-brand-primary text-white uppercase tracking-wide">
                            {{ $project->type }}
                        </span>
                    </div>
                @endif
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">{{ strip_tags(html_entity_decode($project->title)) }}</h1>
                @if($project->client)
                    <p class="mt-6 text-xl text-gray-300">Client: <span class="font-semibold text-white">{{ $project->client }}</span></p>
                @endif
            </div>
        </div>
    </div>

    <!-- Project Content -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-12">
                    @if($project->featured_image)
                        <div class="rounded-2xl overflow-hidden shadow-2xl">
                            <img src="{{ asset('storage/' . $project->featured_image) }}" alt="{{ $project->title }}" class="w-full h-auto">
                        </div>
                    @endif

                    <!-- Project Gallery -->
                    @if($project->gallery && count($project->gallery) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($project->gallery as $image)
                                <div class="rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Project gallery image" class="w-full h-64 object-cover hover:scale-105 transition-transform duration-500">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="prose prose-lg prose-red max-w-none">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">About the Project</h2>
                        {!! $project->description !!}
                    </div>

                    @if($project->impact_metrics)
                    <div class="mt-12 bg-gray-50 rounded-2xl p-8 border border-gray-100 shadow-inner">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 text-brand-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            Results & Impact
                        </h2>
                        <div class="prose prose-lg prose-red max-w-none">
                            {!! $project->impact_metrics !!}
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-8">
                    <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Project Details</h3>
                        <dl class="space-y-4">
                            @if($project->client)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Client</dt>
                                    <dd class="mt-1 text-base font-semibold text-gray-900">{{ $project->client }}</dd>
                                </div>
                            @endif
                            
                            @if($project->year)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Year</dt>
                                    <dd class="mt-1 text-base font-semibold text-gray-900">{{ $project->year }}</dd>
                                </div>
                            @endif

                            @if($project->industry)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-2">Industry</dt>
                                    <dd>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white border border-gray-200 text-gray-800">
                                            {{ $project->industry->name }}
                                        </span>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div class="bg-gradient-to-br from-brand-primary to-brand-secondary rounded-2xl p-8 text-white text-center shadow-xl">
                        <h3 class="text-xl font-bold mb-4">Start Your Next Big Idea</h3>
                        <p class="text-gray-100 mb-8">Ready to achieve similar results? Let's build something extraordinary together.</p>
                        <a href="/start-project" class="inline-block bg-white text-brand-primary font-bold py-3 px-8 rounded-full hover:bg-gray-50 hover:-translate-y-1 transition-all duration-300 shadow-lg">
                            Start a Project
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-gray-200">
                <a href="{{ route('portfolio.index') }}" class="inline-flex items-center text-brand-primary font-medium hover:text-red-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Portfolio
                </a>
            </div>
        </div>
    </div>
</x-home::layouts.master>
