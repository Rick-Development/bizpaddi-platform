<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-brand-secondary  px-6">
    <div class="flex h-16 shrink-0 items-center">
        <span class="text-xl font-bold text-white">Team Portal <!-- DEBUG: MODULE SIDEBAR --></span>
    </div>
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    @if(auth()->user()->isBdm())
                    <li>
                        <a href="{{ route('team.bdm.dashboard') }}" class="{{ request()->routeIs('team.bdm.dashboard') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM15.75 15.75l-2.25 2.25 2.25 2.25" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.bdm.leads.index') }}" class="{{ request()->routeIs('team.bdm.leads.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                            </svg>
                            All Leads
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.bdm.deals.kanban') }}" class="{{ request()->routeIs('team.bdm.deals.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v14.25A2.25 2.25 0 005.25 19.5h13.5a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0018.75 4.5H5.25a2.25 2.25 0 00-2.25 2.25zM6.75 6.75h.75v.75h-.75v-.75zm0 3h.75v.75h-.75v-.75zm0 3h.75v.75h-.75v-.75zM11.25 6.75h7.5v.75h-7.5v-.75zm0 3h7.5v.75h-7.5v-.75zm0 3h7.5v.75h-7.5v-.75z" />
                            </svg>
                            Deal Pipeline
                        </a>
                    </li>
                    @elseif(auth()->user()->isProjectManager())
                    <li>
                        <a href="{{ route('team.pm.dashboard') }}" class="{{ request()->routeIs('team.pm.dashboard') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM15.75 15.75l-2.25 2.25 2.25 2.25" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    @else
                    <li>
                        <a href="{{ route('team.dashboard') }}" class="{{ request()->routeIs('team.dashboard') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('team.projects.index') }}" class="{{ request()->routeIs('team.projects.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                           <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                            </svg>
                            My Projects
                        </a>
                    </li>
                    @unless(auth()->user()->isProjectManager())
                    <li>
                        <a href="{{ route('team.tasks.index') }}" class="{{ request()->routeIs('team.tasks.index') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                           <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            My Tasks
                        </a>
                    </li>
                    @endunless
                    <li>
                        <a href="{{ route('team.kanban.index') }}" class="{{ request()->routeIs('team.kanban.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                           <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v12.75c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            Task Board
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.chat.index') }}" class="{{ request()->routeIs('team.chat.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200 justify-between items-center">
                            <span class="flex gap-x-3 items-center">
                                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025 10.338 10.338 0 01-1.99-4.774 10.314 10.314 0 011.99-4.774M21 12c0-4.556-4.03-8.25-9-8.25a9.764 9.764 0 00-2.555.337A5.972 5.972 0 005.41 3.03a5.97 5.97 0 00-.474.065 4.48 4.48 0 01.978 2.025 10.338 10.338 0 00-1.99 4.774" />
                                </svg>
                                Chat
                            </span>
                            <span id="sidebar-chat-badge" class="bg-red-500 text-white rounded-full px-2 py-0.5 text-[10px] font-bold hidden">0</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.comments-feed.index') }}" class="{{ request()->routeIs('team.comments-feed.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200 justify-between items-center">
                            <span class="flex gap-x-3 items-center">
                                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                </svg>
                                Comments Feed
                            </span>
                            <span id="sidebar-comments-badge" class="bg-red-500 text-white rounded-full px-2 py-0.5 text-[10px] font-bold hidden">0</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.work-logs.index') }}" class="{{ request()->routeIs('team.work-logs.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                             <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Work Session Logs
                        </a>
                    </li>
                </ul>
            </li>



            @if(auth()->user()->isProjectManager())
            <li>
                <div class="text-[10px] font-bold leading-6 text-brand-primary uppercase tracking-[0.2em] mb-3 ml-2 opacity-80">Project Manager</div>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('team.pm.dashboard') }}" class="{{ request()->is('team/pm/dashboard') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->is('team/pm/dashboard') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM15.75 15.75l-2.25 2.25 2.25 2.25" />
                            </svg>
                            Control Center
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.pm.projects.index') }}" class="{{ request()->is('team/pm/projects*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->is('team/pm/projects*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v14.25A2.25 2.25 0 005.25 19.5h13.5a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0018.75 4.5H5.25a2.25 2.25 0 00-2.25 2.25zM6.75 6.75h.75v.75h-.75v-.75zm0 3h.75v.75h-.75v-.75zm0 3h.75v.75h-.75v-.75zM11.25 6.75h7.5v.75h-7.5v-.75zm0 3h7.5v.75h-7.5v-.75zm0 3h7.5v.75h-7.5v-.75z" />
                            </svg>
                            Project Portfolio
                        </a>
                    </li>
                </ul>
            </li>            
            @endif

            @if(auth()->user()->isBdm())
            <li>
                <div class="text-[10px] font-bold leading-6 text-brand-primary uppercase tracking-[0.2em] mb-3 ml-2 opacity-80">Communications</div>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('team.bdm.campaigns.index') }}" class="{{ request()->routeIs('team.bdm.campaigns.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.campaigns.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014-5.395c-.318.557-.614 1.126-.887 1.704M19.175 4.125c.162.3.315.607.46 9.19m-.46-9.19a23.847 23.847 0 018.835 2.535M19.175 4.125c.162.3.315.607.46 9.19m-.46-9.19a23.847 23.847 0 008.835-2.535" />
                            </svg>
                            Campaigns
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.bdm.contact-lists.index') }}" class="{{ request()->routeIs('team.bdm.contact-lists.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.contact-lists.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            Contact Lists
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.bdm.contacts.index') }}" class="{{ request()->routeIs('team.bdm.contacts.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.contacts.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            All Contacts
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.bdm.communication-scripts.index') }}" class="{{ request()->routeIs('team.bdm.communication-scripts.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.communication-scripts.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                            Scripts & Playbooks
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.bdm.email-templates.index') }}" class="{{ request()->routeIs('team.bdm.email-templates.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.email-templates.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            Email Templates
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.bdm.sms-templates.index') }}" class="{{ request()->routeIs('team.bdm.sms-templates.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.sms-templates.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                            </svg>
                            SMS Templates
                        </a>
                    </li>

                </ul>
            </li>            
            <li>
                <div class="text-[10px] font-bold leading-6 text-brand-primary uppercase tracking-[0.2em] mb-3 ml-2 opacity-80 mt-6">Sales & Conversion</div>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('team.bdm.quotes.index') }}" class="{{ request()->routeIs('team.bdm.quotes.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.quotes.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Quotes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.bdm.contracts.index') }}" class="{{ request()->routeIs('team.bdm.contracts.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.contracts.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            Contracts
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <div class="text-[10px] font-bold leading-6 text-brand-primary uppercase tracking-[0.2em] mb-3 ml-2 opacity-80 mt-6">Account Management</div>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('team.bdm.clients.index') }}" class="{{ request()->routeIs('team.bdm.clients.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.clients.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            Clients
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.bdm.service-orders.index') }}" class="{{ request()->routeIs('team.bdm.service-orders.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.service-orders.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            Service Orders
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.bdm.invoices.index') }}" class="{{ request()->routeIs('team.bdm.invoices.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.invoices.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.009 9.55h.008v.008h-.008v-.008zm.009 2.15h.008v.008h-.008v-.008zm.009-2.15h.008v.008h-.008v-.008zm.009 2.149h.008v.008h-.008v-.008zM12 14.75h.008v.008H12v-.008zm0 2.15h.008v.008H12v-.008zm0-2.15h.008v.008H12v-.008zm0 2.149h.008v.008H12v-.008zM15.75 14.75h.008v.008h-.008v-.008zm0 2.15h.008v.008h-.008v-.008zm0-2.15h.008v.008h-.008v-.008zm0 2.149h.008v.008h-.008v-.008zM3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            Invoices
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <div class="text-[10px] font-bold leading-6 text-brand-primary uppercase tracking-[0.2em] mb-3 ml-2 opacity-80 mt-6">Sales Enablement</div>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('team.bdm.services.index') }}" class="{{ request()->routeIs('team.bdm.services.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.services.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            Services Catalog
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.bdm.projects.index') }}" class="{{ request()->routeIs('team.bdm.projects.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.projects.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v14.25A2.25 2.25 0 005.25 19.5h13.5a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0018.75 4.5H5.25a2.25 2.25 0 00-2.25 2.25zM6.75 6.75h.75v.75h-.75v-.75zm0 3h.75v.75h-.75v-.75zm0 3h.75v.75h-.75v-.75zM11.25 6.75h7.5v.75h-7.5v-.75zm0 3h7.5v.75h-7.5v-.75zm0 3h7.5v.75h-7.5v-.75z" />
                            </svg>
                            Portfolio
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.bdm.lead-sources.index') }}" class="{{ request()->routeIs('team.bdm.lead-sources.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.lead-sources.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                            </svg>
                            Lead Sources
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <div class="text-[10px] font-bold leading-6 text-brand-primary uppercase tracking-[0.2em] mb-3 ml-2 opacity-80 mt-6">Help & Support</div>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('team.bdm.tickets.index') }}" class="{{ request()->routeIs('team.bdm.tickets.*') ? 'bg-brand-primary/10 text-brand-primary border-r-2 border-brand-primary' : 'text-gray-300 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-xl p-2.5 text-sm leading-6 font-bold transition-all duration-300">
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('team.bdm.tickets.*') ? 'text-brand-primary' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                            </svg>
                            Support Tickets
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            <li>
                <div class="text-xs font-semibold leading-6 text-brand-primary uppercase tracking-widest mb-2 font-mono ml-2">Growth</div>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('team.performance.index') }}" class="{{ request()->routeIs('team.performance.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                             <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-3.362 8.287 8.287 0 002.001-1.024z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214l-4.517 9.034a.75.75 0 01-1.077.306l-2.001-1.334m6.345-8.006A8.24 8.24 0 0112 4.5c-4.556 0-8.25 3.694-8.25 8.25s3.694 8.25 8.25 8.25 8.25-3.694 8.25-8.25c0-.858-.13-1.685-.373-2.463z" />
                            </svg>
                            Performance & KPIs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team.payments.index') }}" class="{{ request()->routeIs('team.payments.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                             <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            My Earnings
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <div class="text-xs font-semibold leading-6 text-brand-primary uppercase tracking-widest mb-2 font-mono ml-2">Resources</div>
                <ul role="list" class="-mx-2 space-y-1">
                     <li>
                        <a href="{{ route('team.bank-accounts.index') }}" class="{{ request()->routeIs('team.bank-accounts.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                             <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                            </svg>
                            Bank Accounts
                        </a>
                    </li>
                    @can('clients.view')
                    <li>
                        <a href="{{ route('team.clients.index') }}" class="{{ request()->routeIs('team.clients.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            Clients
                        </a>
                    </li>
                    @endcan

                    @can('quotes.view')
                    <li>
                        <a href="{{ route('team.quotes.index') }}" class="{{ request()->routeIs('team.quotes.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Quotes
                        </a>
                    </li>
                    @endcan

                    @can('services.view')
                    <li>
                        <a href="{{ route('team.services.index') }}" class="{{ request()->routeIs('team.services.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                           <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            Services
                        </a>
                    </li>
                    @endcan

                    @can('invoices.view')
                    <li>
                        <a href="{{ route('team.invoices.index') }}" class="{{ request()->routeIs('team.invoices.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                           <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.009 9.55h.008v.008h-.008v-.008zm.009 2.15h.008v.008h-.008v-.008zm.009-2.15h.008v.008h-.008v-.008zm.009 2.149h.008v.008h-.008v-.008zM12 14.75h.008v.008H12v-.008zm0 2.15h.008v.008H12v-.008zm0-2.15h.008v.008H12v-.008zm0 2.149h.008v.008H12v-.008zM15.75 14.75h.008v.008h-.008v-.008zm0 2.15h.008v.008h-.008v-.008zm0-2.15h.008v.008h-.008v-.008zm0 2.149h.008v.008h-.008v-.008zM3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            Invoices
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            
            <li>
                <div class="text-xs font-semibold leading-6 text-brand-primary uppercase tracking-widest mb-2 font-mono ml-2">Settings</div>
                <ul role="list" class="-mx-2 space-y-1">
                         <li>
                        <a href="{{ route('team.profile.index') }}" class="{{ request()->routeIs('team.profile.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                             <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            My Profile
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mt-auto mb-4">
                <form method="POST" action="{{ route('team.logout') }}">
                    @csrf
                    <button type="submit" class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-300 hover:bg-brand-primary/50 hover:text-white w-full">
                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Log out
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</div>

<script>
    function updateSidebarBadges() {
        if (typeof axios === 'undefined') return;

        // Chat unread count
        axios.get("{{ route('team.chat.unread-count') }}")
            .then(res => {
                const badge = document.getElementById('sidebar-chat-badge');
                if (badge) {
                    const count = res.data.unread_count;
                    if (count > 0) {
                        badge.innerText = count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            }).catch(() => {});

        // Comments unread count
        axios.get("{{ route('team.comments-feed.unread-count') }}")
            .then(res => {
                const badge = document.getElementById('sidebar-comments-badge');
                if (badge) {
                    const count = res.data.unread_count;
                    if (count > 0) {
                        badge.innerText = count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            }).catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Run initial load
        setTimeout(updateSidebarBadges, 1000);
        // Poll every 15 seconds
        setInterval(updateSidebarBadges, 15000);

        // Echo listeners for instant sidebar updates
        if (window.Echo) {
            window.Echo.private('task-comments')
                .listen('.Modules\\ProjectManagement\\Events\\CommentPosted', (e) => {
                    // Instantly trigger unread count refresh
                    updateSidebarBadges();
                });
        }
    });
</script>

