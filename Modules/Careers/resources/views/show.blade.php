<x-home::layouts.master>
    @section('title', strip_tags(html_entity_decode($job->title)) . ' - Careers at Bizpaddi')
    @section('description', Str::limit(strip_tags(html_entity_decode($job->description)), 160))

    @php
        $renderFormattedContent = function($content) {
            if (empty($content)) return '';
            // If content already contains HTML tags
            if (preg_match('/<[a-z][\s\S]*>/i', $content)) {
                return $content;
            }
            // Otherwise intelligently format plain text into paragraphs and bullet lists
            $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $content));
            $html = '';
            $inList = false;
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (empty($trimmed)) {
                    if ($inList) { $html .= "</ul>\n"; $inList = false; }
                    continue;
                }
                if (str_starts_with($trimmed, '•') || str_starts_with($trimmed, '-') || str_starts_with($trimmed, '*')) {
                    if (!$inList) { 
                        $html .= "<ul class=\"space-y-2.5 my-4\">\n"; 
                        $inList = true; 
                    }
                    $item = trim(ltrim($trimmed, "•-* \t"));
                    $html .= "<li class=\"flex items-start gap-3\"><span class=\"flex-shrink-0 w-2 h-2 rounded-full bg-brand-primary mt-2\"></span><span>" . e($item) . "</span></li>\n";
                } else {
                    if ($inList) { $html .= "</ul>\n"; $inList = false; }
                    // Check if it's a section title
                    if (str_ends_with($trimmed, ':')) {
                        $html .= "<h4 class=\"text-lg font-bold text-gray-900 dark:text-white mt-6 mb-3\">" . e($trimmed) . "</h4>\n";
                    } else {
                        $html .= "<p class=\"text-gray-600 dark:text-gray-300 leading-relaxed mb-4\">" . e($trimmed) . "</p>\n";
                    }
                }
            }
            if ($inList) { $html .= "</ul>\n"; }
            return $html;
        };
    @endphp

    <!-- Hero Header -->
    <div class="relative py-16 sm:py-24 overflow-hidden border-b" style="background: linear-gradient(135deg, #10231C 0%, #0d102b 50%, #070919 100%) !important; color: #ffffff !important; border-color: rgba(255, 255, 255, 0.1) !important;">
        <!-- Ambient Decorative Glows -->
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full blur-3xl pointer-events-none" style="background: rgba(251, 76, 75, 0.18);"></div>
        <div class="absolute top-1/2 right-0 w-96 h-96 rounded-full blur-3xl pointer-events-none" style="background: rgba(147, 51, 234, 0.18);"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs sm:text-sm mb-6" style="color: #94a3b8 !important;">
                <a href="/" class="transition-colors hover:underline" style="color: #cbd5e1 !important;">Home</a>
                <span style="color: #64748b !important;">/</span>
                <a href="{{ route('careers.index') }}" class="transition-colors hover:underline" style="color: #cbd5e1 !important;">Careers</a>
                <span style="color: #64748b !important;">/</span>
                <span class="truncate" style="color: #f8fafc !important; font-weight: 500;">{{ $job->department }}</span>
            </nav>

            <!-- Badges Bar -->
            <div class="flex items-center gap-2.5 flex-wrap mb-4">
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider" style="background: rgba(255, 255, 255, 0.12) !important; color: #ffffff !important; border: 1px solid rgba(255, 255, 255, 0.25) !important;">
                    {{ $job->department }}
                </span>

                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold" style="background: rgba(16, 185, 129, 0.2) !important; color: #6ee7b7 !important; border: 1px solid rgba(16, 185, 129, 0.4) !important;">
                    <span class="w-2 h-2 rounded-full mr-2" style="background: #10b981 !important; box-shadow: 0 0 8px #10b981;"></span>
                    Actively Hiring
                </span>

                @if($job->is_featured)
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold" style="background: rgba(245, 158, 11, 0.2) !important; color: #fde047 !important; border: 1px solid rgba(245, 158, 11, 0.4) !important;">
                    <svg class="w-3.5 h-3.5 fill-current" style="color: #fbbf24 !important;" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Featured Role
                </span>
                @endif
            </div>

            <!-- Job Title -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight mb-6 leading-tight" style="color: #ffffff !important;">
                {{ $job->title }}
            </h1>

            <!-- Key Metadata Strip -->
            <div class="flex flex-wrap items-center gap-y-3 gap-x-6 text-sm pb-8 border-b" style="border-color: rgba(255, 255, 255, 0.15) !important;">
                <!-- Location -->
                <div class="flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg" style="background: rgba(255, 255, 255, 0.12) !important;">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span style="color: #f1f5f9 !important; font-weight: 500;">{{ $job->location }}</span>
                </div>

                <!-- Workplace & Type -->
                <div class="flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg" style="background: rgba(255, 255, 255, 0.12) !important;">
                        <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <span style="color: #f1f5f9 !important; font-weight: 500;">{{ ucfirst(str_replace('-', ' ', $job->type)) }} &bull; {{ $job->workplace_label }}</span>
                </div>

                @if($job->experience_level)
                <!-- Experience -->
                <div class="flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg" style="background: rgba(255, 255, 255, 0.12) !important;">
                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <span style="color: #f1f5f9 !important; font-weight: 500;">{{ $job->experience_level }}</span>
                </div>
                @endif

                @if($job->formatted_salary)
                <!-- Compensation -->
                <div class="flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg" style="background: rgba(16, 185, 129, 0.2) !important;">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span style="color: #6ee7b7 !important; font-weight: 700;">{{ $job->formatted_salary }}</span>
                </div>
                @endif

                @if($job->application_deadline)
                <!-- Deadline -->
                <div class="flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg" style="background: rgba(255, 255, 255, 0.12) !important;">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <span style="color: #cbd5e1 !important;">Apply before: <span style="color: #ffffff !important; font-weight: 600;">{{ $job->application_deadline->format('M d, Y') }}</span></span>
                </div>
                @endif
            </div>

            <!-- Hero Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <a href="#apply" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-bold shadow-xl transform hover:-translate-y-0.5 transition-all duration-200" style="background: linear-gradient(135deg, #0E5E4A 0%, #a855f7 100%) !important; color: #ffffff !important; box-shadow: 0 10px 25px -5px rgba(251, 76, 75, 0.4) !important;">
                    Apply for this Role
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>

                <button type="button" onclick="copyJobLink()" id="hero-copy-btn" class="inline-flex items-center justify-center px-6 py-3.5 rounded-full text-sm font-semibold transition-all shadow-sm" style="background: rgba(255, 255, 255, 0.12) !important; color: #ffffff !important; border: 1px solid rgba(255, 255, 255, 0.25) !important;">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                    <span id="hero-copy-text" style="color: #ffffff !important;">Share Job Posting</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="bg-gray-50/60 dark:bg-gray-900 py-16 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                <!-- Left / Main Column (lg:col-span-8) -->
                <div class="lg:col-span-8 space-y-10">

                    <!-- Section: Role Overview -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 sm:p-10 shadow-sm border border-gray-200/80 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="w-10 h-10 rounded-xl bg-brand-primary/10 flex items-center justify-center text-brand-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">About the Role</h2>
                        </div>

                        <div class="prose prose-lg dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                            {!! $renderFormattedContent($job->description) !!}
                        </div>
                    </div>

                    <!-- Section: Key Responsibilities (if present) -->
                    @if($job->responsibilities)
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 sm:p-10 shadow-sm border border-gray-200/80 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Key Responsibilities</h2>
                        </div>

                        <div class="prose prose-lg dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                            {!! $renderFormattedContent($job->responsibilities) !!}
                        </div>
                    </div>
                    @endif

                    <!-- Section: Requirements & Qualifications -->
                    @if($job->requirements)
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 sm:p-10 shadow-sm border border-gray-200/80 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-600 dark:text-purple-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Requirements & Qualifications</h2>
                        </div>

                        <div class="prose prose-lg dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                            {!! $renderFormattedContent($job->requirements) !!}
                        </div>
                    </div>
                    @endif

                    <!-- Section: Skills & Tech Stack -->
                    @if(count($job->skills_list) > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 sm:p-10 shadow-sm border border-gray-200/80 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Technologies & Tools</h2>
                        </div>

                        <div class="flex flex-wrap gap-2.5">
                            @foreach($job->skills_list as $skill)
                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-brand-primary/10 hover:text-brand-primary transition-colors border border-gray-200/60 dark:border-gray-600">
                                {{ $skill }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Section: Benefits & What We Offer -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 sm:p-10 shadow-sm border border-gray-200/80 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">What We Offer</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Perks, growth, and team culture at Bizpaddi</p>
                            </div>
                        </div>

                        @if($job->benefits)
                        <div class="prose prose-lg dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed mb-8">
                            {!! $renderFormattedContent($job->benefits) !!}
                        </div>
                        @endif

                        <!-- Standard Perks Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-5 rounded-2xl bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700/70">
                                <div class="w-9 h-9 rounded-xl bg-brand-primary/10 text-brand-primary flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <h3 class="font-bold text-gray-900 dark:text-white text-base mb-1">Global Impact</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Build high-performance web, mobile, and fintech products for international clients.</p>
                            </div>

                            <div class="p-5 rounded-2xl bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700/70">
                                <div class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <h3 class="font-bold text-gray-900 dark:text-white text-base mb-1">Flexible & Remote First</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Enjoy modern autonomy and work-life balance from where you do your best work.</p>
                            </div>

                            <div class="p-5 rounded-2xl bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700/70">
                                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <h3 class="font-bold text-gray-900 dark:text-white text-base mb-1">Competitive Pay & Incentives</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Timely compensation, transparent performance evaluations, and milestone bonuses.</p>
                            </div>

                            <div class="p-5 rounded-2xl bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700/70">
                                <div class="w-9 h-9 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                </div>
                                <h3 class="font-bold text-gray-900 dark:text-white text-base mb-1">Continuous Career Growth</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Direct mentorship from senior technical leads and access to modern tools & frameworks.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Application Form Card -->
                    <div id="apply" class="relative bg-white dark:bg-gray-800 rounded-3xl p-8 sm:p-12 shadow-xl border-2 border-brand-primary/20 dark:border-brand-primary/30 scroll-mt-24">
                        <div class="mb-8">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-primary/10 text-brand-primary mb-3">
                                Quick Application
                            </span>
                            <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white">Apply for this Position</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Complete the brief form below and upload your resume. Our technical hiring team will review your application within 2-3 business days.
                            </p>
                        </div>

                        <!-- Success Alert -->
                        @if(session('success'))
                        <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 p-6 mb-8 border border-emerald-200 dark:border-emerald-800">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-800 flex items-center justify-center text-emerald-600 dark:text-emerald-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-base font-bold text-emerald-900 dark:text-emerald-200">Application Received!</h4>
                                    <p class="mt-1 text-sm text-emerald-700 dark:text-emerald-300">
                                        {{ session('success') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Form Error Summary -->
                        @if($errors->any())
                        <div class="rounded-2xl bg-red-50 dark:bg-red-900/30 p-5 mb-8 border border-red-200 dark:border-red-800">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <h4 class="text-sm font-bold text-red-800 dark:text-red-200">Please correct the following:</h4>
                                    <ul class="mt-1.5 list-disc list-inside text-xs text-red-700 dark:text-red-300 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif

                        <form action="{{ route('careers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="job-application-form">
                            @csrf
                            <input type="hidden" name="job_id" value="{{ $job->id }}">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Full Name -->
                                <div class="sm:col-span-2">
                                    <label for="name" class="block text-sm font-bold text-gray-900 dark:text-white">Full Name <span class="text-red-500">*</span></label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </div>
                                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                               placeholder="John Doe"
                                               class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/60 pl-10 py-3 px-4 text-gray-900 dark:text-white placeholder-gray-400 focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                                    </div>
                                    @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <!-- Email Address -->
                                <div>
                                    <label for="email" class="block text-sm font-bold text-gray-900 dark:text-white">Email Address <span class="text-red-500">*</span></label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </div>
                                        <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                               placeholder="john@example.com"
                                               class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/60 pl-10 py-3 px-4 text-gray-900 dark:text-white placeholder-gray-400 focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                                    </div>
                                    @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <label for="phone" class="block text-sm font-bold text-gray-900 dark:text-white">Phone Number</label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        </div>
                                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                               placeholder="+234 800 000 0000"
                                               class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/60 pl-10 py-3 px-4 text-gray-900 dark:text-white placeholder-gray-400 focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                                    </div>
                                    @error('phone')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <!-- LinkedIn Profile -->
                                <div class="sm:col-span-2">
                                    <label for="linkedin" class="block text-sm font-bold text-gray-900 dark:text-white">LinkedIn / Portfolio URL</label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                        </div>
                                        <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin') }}"
                                               placeholder="https://linkedin.com/in/username or https://github.com/username"
                                               class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/60 pl-10 py-3 px-4 text-gray-900 dark:text-white placeholder-gray-400 focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                                    </div>
                                    @error('linkedin')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <!-- Drag & Drop Resume Upload -->
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">
                                        Resume / Curriculum Vitae <span class="text-red-500">*</span>
                                    </label>
                                    <div id="dropzone" class="relative group border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-brand-primary dark:hover:border-brand-primary rounded-2xl p-8 text-center bg-gray-50/50 dark:bg-gray-700/30 transition-all cursor-pointer">
                                        <input type="file" name="resume" id="resume" required accept=".pdf,.doc,.docx"
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        
                                        <div id="upload-idle">
                                            <div class="mx-auto w-12 h-12 rounded-2xl bg-brand-primary/10 text-brand-primary flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                            </div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                <span class="text-brand-primary hover:underline">Click to upload</span> or drag and drop your file here
                                            </p>
                                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">PDF, DOC, DOCX up to 10MB</p>
                                        </div>

                                        <div id="upload-selected" class="hidden">
                                            <div class="mx-auto w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center mb-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <p id="file-name" class="text-sm font-bold text-gray-900 dark:text-white truncate max-w-sm mx-auto"></p>
                                            <p id="file-size" class="mt-1 text-xs text-gray-500 dark:text-gray-400"></p>
                                            <span class="mt-2 inline-block text-xs font-semibold text-brand-primary hover:underline">Click to change file</span>
                                        </div>
                                    </div>
                                    @error('resume')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <!-- Cover Letter -->
                                <div class="sm:col-span-2">
                                    <label for="cover_letter" class="block text-sm font-bold text-gray-900 dark:text-white">Cover Note / Introduction</label>
                                    <div class="mt-2">
                                        <textarea id="cover_letter" name="cover_letter" rows="4"
                                                  placeholder="Tell us briefly why you're a great fit for this position, your relevant achievements, and when you can start..."
                                                  class="block w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700/60 p-4 text-gray-900 dark:text-white placeholder-gray-400 focus:border-brand-primary focus:ring-brand-primary sm:text-sm">{{ old('cover_letter') }}</textarea>
                                    </div>
                                    @error('cover_letter')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <span>Encrypted & confidential candidate review.</span>
                                </div>

                                <button type="submit" id="submit-application-btn" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 rounded-full text-base font-bold text-white bg-gradient-to-r from-brand-primary to-purple-600 hover:from-brand-primary hover:to-purple-700 shadow-xl shadow-brand-primary/25 transform hover:-translate-y-0.5 transition-all">
                                    <span>Submit Application</span>
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                <!-- Right / Sticky Sidebar (lg:col-span-4) -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- Job Summary Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200/80 dark:border-gray-700 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 pb-3 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Role Specifications
                        </h3>

                        <dl class="space-y-4 text-sm">
                            <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-700/50">
                                <dt class="text-gray-500 dark:text-gray-400 font-medium">Department</dt>
                                <dd class="font-bold text-gray-900 dark:text-white text-right">{{ $job->department }}</dd>
                            </div>

                            <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-700/50">
                                <dt class="text-gray-500 dark:text-gray-400 font-medium">Employment Type</dt>
                                <dd class="font-bold text-gray-900 dark:text-white text-right capitalize">{{ str_replace('-', ' ', $job->type) }}</dd>
                            </div>

                            <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-700/50">
                                <dt class="text-gray-500 dark:text-gray-400 font-medium">Workplace</dt>
                                <dd class="font-bold text-gray-900 dark:text-white text-right">{{ $job->workplace_label }}</dd>
                            </div>

                            <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-700/50">
                                <dt class="text-gray-500 dark:text-gray-400 font-medium">Location</dt>
                                <dd class="font-bold text-gray-900 dark:text-white text-right">{{ $job->location }}</dd>
                            </div>

                            @if($job->experience_level)
                            <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-700/50">
                                <dt class="text-gray-500 dark:text-gray-400 font-medium">Experience</dt>
                                <dd class="font-bold text-gray-900 dark:text-white text-right">{{ $job->experience_level }}</dd>
                            </div>
                            @endif

                            @if($job->formatted_salary)
                            <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-700/50">
                                <dt class="text-gray-500 dark:text-gray-400 font-medium">Compensation</dt>
                                <dd class="font-bold text-emerald-600 dark:text-emerald-400 text-right">{{ $job->formatted_salary }}</dd>
                            </div>
                            @endif


                            @if($job->application_deadline)
                            <div class="flex items-center justify-between py-2">
                                <dt class="text-gray-500 dark:text-gray-400 font-medium">Deadline</dt>
                                <dd class="font-bold text-amber-600 dark:text-amber-400 text-right">{{ $job->application_deadline->format('M d, Y') }}</dd>
                            </div>
                            @endif
                        </dl>

                        <div class="mt-6 pt-4">
                            <a href="#apply" class="w-full inline-flex items-center justify-center px-6 py-3 rounded-2xl text-sm font-bold text-white bg-brand-primary hover:bg-brand-primary/90 shadow-lg shadow-brand-primary/25 transition-all text-center">
                                Apply Now
                            </a>
                        </div>
                    </div>

                    <!-- Hiring Process Timeline Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200/80 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Our Hiring Process
                        </h3>

                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-brand-primary/10 text-brand-primary font-bold text-xs flex-shrink-0">1</span>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Application Review</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">We review your resume, experience, and projects.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-500/10 text-blue-600 font-bold text-xs flex-shrink-0">2</span>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Skills Assessment</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">A practical task or portfolio walkthrough.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-purple-500/10 text-purple-600 font-bold text-xs flex-shrink-0">3</span>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Technical & Team Interview</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Chat with our project leads and team members.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-500/10 text-emerald-600 font-bold text-xs flex-shrink-0">4</span>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Offer & Onboarding</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Welcome to the Bizpaddi team!</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Share Role Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200/80 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Share this Opportunity</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">Know someone who would be great for this role?</p>

                        <div class="grid grid-cols-3 gap-3 mb-4">
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="flex flex-col items-center justify-center p-3 rounded-2xl bg-gray-50 dark:bg-gray-700/50 hover:bg-blue-50 hover:text-blue-600 transition-colors border border-gray-100 dark:border-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 mb-1.5 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                LinkedIn
                            </a>

                            <a href="https://twitter.com/intent/tweet?text={{ urlencode('Check out this career opportunity at Bizpaddi: ' . $job->title) }}&url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="flex flex-col items-center justify-center p-3 rounded-2xl bg-gray-50 dark:bg-gray-700/50 hover:bg-sky-50 hover:text-sky-500 transition-colors border border-gray-100 dark:border-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 mb-1.5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                X / Twitter
                            </a>

                            <a href="https://api.whatsapp.com/send?text={{ urlencode('Job opening at Bizpaddi: ' . $job->title . ' - ' . request()->fullUrl()) }}" target="_blank" class="flex flex-col items-center justify-center p-3 rounded-2xl bg-gray-50 dark:bg-gray-700/50 hover:bg-emerald-50 hover:text-emerald-600 transition-colors border border-gray-100 dark:border-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 mb-1.5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/></svg>
                                WhatsApp
                            </a>
                        </div>

                        <button type="button" onclick="copyJobLink()" id="sidebar-copy-btn" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-2xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-xs font-bold text-gray-800 dark:text-gray-200 transition-colors">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            <span id="sidebar-copy-text">Copy Direct Link</span>
                        </button>
                    </div>

                    <!-- Related Roles (if any) -->
                    @if(isset($relatedJobs) && $relatedJobs->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-200/80 dark:border-gray-700">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Other Opportunities</h3>
                        <div class="space-y-4">
                            @foreach($relatedJobs as $relJob)
                            <a href="{{ route('careers.show', $relJob->id) }}" class="block p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/40 hover:bg-brand-primary/5 border border-transparent hover:border-brand-primary/20 transition-all group">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-brand-primary transition-colors">{{ $relJob->title }}</h4>
                                <div class="mt-1 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    <span>{{ $relJob->department }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $relJob->workplace_label }}</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

            </div>

            <!-- Back to Careers -->
            <div class="mt-16 pt-8 border-t border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <a href="{{ route('careers.index') }}" class="inline-flex items-center text-sm font-bold text-brand-primary hover:text-brand-primary/80 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Explore All Openings
                </a>
            </div>
        </div>
    </div>

    <!-- Interactive Scripts for File Dropzone and Link Copying -->
    <script>
        function copyJobLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(function() {
                const heroBtn = document.getElementById('hero-copy-text');
                const sidebarBtn = document.getElementById('sidebar-copy-text');
                if (heroBtn) heroBtn.textContent = 'Link Copied!';
                if (sidebarBtn) sidebarBtn.textContent = 'Link Copied!';

                setTimeout(function() {
                    if (heroBtn) heroBtn.textContent = 'Share Job Posting';
                    if (sidebarBtn) sidebarBtn.textContent = 'Copy Direct Link';
                }, 3000);
            }).catch(function() {
                alert('Job URL: ' + url);
            });
        }

        // File dropzone interactivity
        document.addEventListener('DOMContentLoaded', function() {
            const resumeInput = document.getElementById('resume');
            const idleState = document.getElementById('upload-idle');
            const selectedState = document.getElementById('upload-selected');
            const fileNameEl = document.getElementById('file-name');
            const fileSizeEl = document.getElementById('file-size');

            if (resumeInput) {
                resumeInput.addEventListener('change', function(e) {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        fileNameEl.textContent = file.name;
                        fileSizeEl.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                        idleState.classList.add('hidden');
                        selectedState.classList.remove('hidden');
                    }
                });
            }
        });
    </script>
</x-home::layouts.master>
