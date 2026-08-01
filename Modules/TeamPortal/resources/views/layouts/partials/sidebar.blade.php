<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-brand-secondary  px-6">
    <div class="flex h-16 shrink-0 items-center">
        <span class="text-xl font-bold text-white">Team Portal <!-- DEBUG: MODULE SIDEBAR --></span>
    </div>
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <div class="text-xs font-semibold leading-6 text-brand-primary uppercase tracking-widest mb-2 font-mono ml-2">My Work</div>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('team.dashboard') }}" class="{{ request()->routeIs('team.dashboard') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
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
                        <a href="{{ route('team.work-logs.index') }}" class="{{ request()->routeIs('team.work-logs.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
                             <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Daily Work Logs
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
                        <a href="{{ route('team.profile.edit') }}" class="{{ request()->routeIs('team.profile.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-all duration-200">
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
