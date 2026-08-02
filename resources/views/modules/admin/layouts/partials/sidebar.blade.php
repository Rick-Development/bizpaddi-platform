<div class="flex h-full grow flex-col overflow-y-auto bg-brand-secondary px-6 pb-4">
    <div class="flex h-16 shrink-0 items-center">
        <h1 class="text-xl font-bold text-white">Rick Development</h1>
    </div>
    <nav class="mt-5 flex flex-1 flex-col">
        <ul role="list" class="flex flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="{{ request()->routeIs('admin.dashboard') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            Dashboard
                        </a>
                    </li>

                    <!-- Visitor Sessions -->
                    <li>
                        <a href="{{ route('admin.visitors.index') }}" 
                           class="{{ request()->routeIs('admin.visitors.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Visitor Sessions
                        </a>
                    </li>



                    <!-- AI Center -->
                    <li x-data="{ open: {{ request()->routeIs('admin.ai.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" type="button" class="{{ request()->routeIs('admin.ai.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm leading-6 font-semibold" aria-controls="sub-menu-ai" aria-expanded="false">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                            </svg>
                            <span class="flex-1 text-left">AI Center</span>
                            <svg :class="open ? 'rotate-90 text-gray-500' : 'text-gray-400'" class="ml-auto h-5 w-5 shrink-0 transform transition-transform duration-150 ease-in-out" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <ul x-show="open" x-cloak class="mt-1 px-2 space-y-1" id="sub-menu-ai" style="display: none;">
                            <li>
                                <a href="{{ route('admin.mcp.ai-generator') }}" class="{{ request()->routeIs('admin.mcp.ai-generator') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} block rounded-md py-2 pr-2 pl-11 text-sm leading-6 font-semibold">
                                    AI Generator ✨
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.mcp.index') }}" class="{{ request()->routeIs('admin.mcp.index') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} block rounded-md py-2 pr-2 pl-11 text-sm leading-6 font-semibold">
                                    MCP Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ai.settings.index') }}" class="{{ request()->routeIs('admin.ai.settings.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} block rounded-md py-2 pr-2 pl-11 text-sm leading-6 font-semibold">
                                    Settings
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ai.models.index') }}" class="{{ request()->routeIs('admin.ai.models.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} block rounded-md py-2 pr-2 pl-11 text-sm leading-6 font-semibold">
                                    AI Models
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ai.chats.index') }}" class="{{ request()->routeIs('admin.ai.chats.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} block rounded-md py-2 pr-2 pl-11 text-sm leading-6 font-semibold">
                                    Chat History
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Home Page Management -->
                    <li x-data="{ open: {{ request()->routeIs('admin.hero-sliders.*') || request()->routeIs('admin.featured-content.*') || request()->routeIs('admin.home.*') || request()->routeIs('admin.testimonials.*') ? 'true' : 'false' }} }">
                        <button type="button" class="group w-full flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-300 hover:text-white hover:bg-brand-primary/50" @click="open = !open">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            <span class="flex-1 text-left">Home Page</span>
                            <svg class="h-5 w-5 shrink-0 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <ul x-show="open" class="mt-1 px-2 space-y-1" style="display: none;">
                            <li>
                                <a href="{{ route('admin.hero-sliders.index') }}" class="{{ request()->routeIs('admin.hero-sliders.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Hero Sliders
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.featured-content.index') }}" class="{{ request()->routeIs('admin.featured-content.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Featured Content
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.home.stats.index') }}" class="{{ request()->routeIs('admin.home.stats.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Stats
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.home.features.index') }}" class="{{ request()->routeIs('admin.home.features.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Features
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.home.processes.index') }}" class="{{ request()->routeIs('admin.home.processes.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Processes
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.home.ctas.index') }}" class="{{ request()->routeIs('admin.home.ctas.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    CTAs
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.navigation.index') }}" class="{{ request()->routeIs('admin.navigation.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Navigation
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Testimonials
                                </a>
                            </li>
                        </ul>
                    </li>


                    <!-- Service Orders -->
                    <li>
                        <a href="{{ route('admin.accounting.service-orders.index') }}" 
                           class="{{ request()->routeIs('admin.accounting.service-orders.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            Service Orders
                        </a>
                    </li>

                    <!-- Clients -->
                    <li>
                        <a href="{{ route('admin.clients.index') }}" 
                           class="{{ request()->routeIs('admin.clients.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            Clients
                        </a>
                    </li>

                    <!-- Team Management -->
                    <li x-data="{ open: {{ request()->routeIs('admin.team-members.*') || request()->routeIs('admin.team-performance.*') || request()->routeIs('admin.team-utilities.*') || request()->routeIs('admin.departments.*') || request()->routeIs('admin.roles.*') ? 'true' : 'false' }} }">
                        <button type="button" class="group w-full flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-300 hover:text-white hover:bg-brand-primary/50" @click="open = !open">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                            <span class="flex-1 text-left">Team Management</span>
                            <svg class="h-5 w-5 shrink-0 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <ul x-show="open" class="mt-1 px-2 space-y-1" style="display: none;">
                            <li>
                                <a href="{{ route('admin.team-members.index') }}" class="{{ request()->routeIs('admin.team-members.*') && !request()->routeIs('admin.team-performance.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Team Members
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.team-performance.index') }}" class="{{ request()->routeIs('admin.team-performance.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Team Performance
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.team-utilities.index') }}" class="{{ request()->routeIs('admin.team-utilities.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Team Utilities
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.departments.index') }}" class="{{ request()->routeIs('admin.departments.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Departments
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Roles (RBAC)
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Accounting -->
                    <li x-data="{ open: {{ request()->routeIs('admin.accounting.*') ? 'true' : 'false' }} }">
                        <button type="button" class="group w-full flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-300 hover:text-white hover:bg-brand-primary/50" @click="open = !open">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                            <span class="flex-1 text-left">Accounting</span>
                            <svg class="h-5 w-5 shrink-0 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <ul x-show="open" class="mt-1 px-2 space-y-1" style="display: none;">
                            <li>
                                <a href="{{ route('admin.accounting.invoices.index') }}" class="{{ request()->routeIs('admin.accounting.invoices.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Invoices
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.accounting.quotes.index') }}" class="{{ request()->routeIs('admin.accounting.quotes.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Quotes
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.accounting.transactions.index') }}" class="{{ request()->routeIs('admin.accounting.transactions.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Transactions
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.accounting.bank.transactions') }}" class="{{ request()->routeIs('admin.accounting.bank.transactions') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Bank Transactions
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.accounting.salaries.index') }}" class="{{ request()->routeIs('admin.accounting.salaries.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Salary Disbursement
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.accounting.accounts.index') }}" class="{{ request()->routeIs('admin.accounting.accounts.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Accounts
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.accounting.balance-sheet') }}" class="{{ request()->routeIs('admin.accounting.balance-sheet') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Balance Sheet
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.accounting.services.index') }}" class="{{ request()->routeIs('admin.accounting.services.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Services
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.accounting.service-categories.index') }}" class="{{ request()->routeIs('admin.accounting.service-categories.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Service Categories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.accounting.feature-pricing.index') }}" class="{{ request()->routeIs('admin.accounting.feature-pricing.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Feature Pricing
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.accounting.currencies.index') }}" class="{{ request()->routeIs('admin.accounting.currencies.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Currencies
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings.index') }}#payment-gateway" class="{{ request()->fullUrlIs(route('admin.settings.index') . '#payment-gateway') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Payment Settings
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Contracts -->
                    <li x-data="{ open: {{ request()->routeIs('admin.contracts.*') ? 'true' : 'false' }} }">
                        <button type="button" class="group w-full flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-300 hover:text-white hover:bg-brand-primary/50" @click="open = !open">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <span class="flex-1 text-left">Contracts</span>
                            <svg class="h-5 w-5 shrink-0 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <ul x-show="open" class="mt-1 px-2 space-y-1" style="display: none;">
                            <li>
                                <a href="{{ route('admin.contracts.index') }}" class="{{ request()->routeIs('admin.contracts.index') || request()->routeIs('admin.contracts.show') || request()->routeIs('admin.contracts.edit') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    All Contracts
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.contracts.create') }}" class="{{ request()->routeIs('admin.contracts.create') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Create Contract
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.contracts.expiring') }}" class="{{ request()->routeIs('admin.contracts.expiring') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Expiring Soon
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.contracts.templates.index') }}" class="{{ request()->routeIs('admin.contracts.templates.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Templates
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Portfolio Management -->
                    <li x-data="{ open: {{ request()->routeIs('admin.projects.*') || request()->routeIs('admin.industries.*') ? 'true' : 'false' }} }">
                        <button type="button" class="group w-full flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-300 hover:text-white hover:bg-brand-primary/50" @click="open = !open">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <span class="flex-1 text-left">Portfolio Management</span>
                            <svg class="h-5 w-5 shrink-0 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <ul x-show="open" class="mt-1 px-2 space-y-1" style="display: none;">
                            <li>
                                <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Projects
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.industries.index') }}" class="{{ request()->routeIs('admin.industries.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Industries
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Content Management -->
                    <li x-data="{ open: {{ request()->routeIs('admin.services.*') || request()->routeIs('admin.testimonials.*') || request()->routeIs('admin.posts.*') || request()->routeIs('admin.categories.*') ? 'true' : 'false' }} }">
                        <button type="button" class="group w-full flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-300 hover:text-white hover:bg-brand-primary/50" @click="open = !open">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <span class="flex-1 text-left">Content</span>
                            <svg class="h-5 w-5 shrink-0 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <ul x-show="open" class="mt-1 px-2 space-y-1" style="display: none;">
                            <li>
                                <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Services
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Testimonials
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Blog Posts
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Blog Categories
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Project Management -->
                    <li x-data="{ open: {{ request()->routeIs('admin.pm-projects.*') || request()->routeIs('admin.tasks.*') || request()->routeIs('admin.work-logs.*') || request()->routeIs('admin.pm-milestones.*') || request()->routeIs('admin.kanban.*') || request()->routeIs('admin.team-monitoring.*') || request()->routeIs('admin.kpis.*') ? 'true' : 'false' }} }">
                        <button type="button" class="group w-full flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-300 hover:text-white hover:bg-brand-primary/50" @click="open = !open">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v12.75c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            <span class="flex-1 text-left">Project Management</span>
                            <svg class="h-5 w-5 shrink-0 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <ul x-show="open" class="mt-1 px-2 space-y-1" style="display: none;">
                            <li>
                                <a href="{{ route('admin.pm-projects.index') }}" class="{{ request()->routeIs('admin.pm-projects.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Projects
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.pm-milestones.index') }}" class="{{ request()->routeIs('admin.pm-milestones.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Milestones
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.kanban.index') }}" class="{{ request()->routeIs('admin.kanban.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Tasks (Kanban)
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.work-logs.index') }}" class="{{ request()->routeIs('admin.work-logs.index') || request()->routeIs('admin.work-logs.show') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Work Session Logs
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.work-logs.export-page') }}" class="{{ request()->routeIs('admin.work-logs.export-page') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Export Generator
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.team-monitoring.index') }}" class="{{ request()->routeIs('admin.team-monitoring.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Team Monitoring
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.kpis.index') }}" class="{{ request()->routeIs('admin.kpis.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    KPI Management
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.team-members.index') }}" class="{{ request()->routeIs('admin.team-members.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Team Workloads
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Company Info -->
                    <li x-data="{ open: {{ request()->routeIs('admin.leaders.*') || request()->routeIs('admin.timeline.*') || request()->routeIs('admin.awards.*') || request()->routeIs('admin.subsidiaries.*') || request()->routeIs('admin.legal-pages.*') ? 'true' : 'false' }} }">
                        <button type="button" class="group w-full flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-300 hover:text-white hover:bg-brand-primary/50" @click="open = !open">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                            <span class="flex-1 text-left">Company</span>
                            <svg class="h-5 w-5 shrink-0 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <ul x-show="open" class="mt-1 px-2 space-y-1" style="display: none;">
                            <li>
                                <a href="{{ route('admin.leaders.index') }}" class="{{ request()->routeIs('admin.leaders.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Leaders
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.timeline.index') }}" class="{{ request()->routeIs('admin.timeline.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Timeline
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.awards.index') }}" class="{{ request()->routeIs('admin.awards.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Awards
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.subsidiaries.index') }}" class="{{ request()->routeIs('admin.subsidiaries.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Subsidiaries
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.legal-pages.index') }}" class="{{ request()->routeIs('admin.legal-pages.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Legal Pages
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Investor Relations -->
                    <li x-data="{ open: {{ request()->routeIs('admin.investor.*') ? 'true' : 'false' }} }">
                        <button type="button" class="group w-full flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-300 hover:text-white hover:bg-brand-primary/50" @click="open = !open">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                            </svg>
                            <span class="flex-1 text-left">Investor Relations</span>
                            <svg class="h-5 w-5 shrink-0 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <ul x-show="open" class="mt-1 px-2 space-y-1" style="display: none;">
                            <li>
                                <a href="{{ route('admin.investor.financial-reports.index') }}" class="{{ request()->routeIs('admin.investor.financial-reports.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Financial Reports
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.investor.press-releases.index') }}" class="{{ request()->routeIs('admin.investor.press-releases.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Press Releases
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.investor.stock-data.index') }}" class="{{ request()->routeIs('admin.investor.stock-data.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Stock Data
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.investor.shareholder-meetings.index') }}" class="{{ request()->routeIs('admin.investor.shareholder-meetings.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Shareholder Meetings
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Recruitment -->
                    <li x-data="{ open: {{ request()->routeIs('admin.jobs.*') || request()->routeIs('admin.applications.*') ? 'true' : 'false' }} }">
                        <button type="button" class="group w-full flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-300 hover:text-white hover:bg-brand-primary/50" @click="open = !open">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <span class="flex-1 text-left">Recruitment</span>
                            <svg class="h-5 w-5 shrink-0 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <ul x-show="open" class="mt-1 px-2 space-y-1" style="display: none;">
                            <li>
                                <a href="{{ route('admin.jobs.index') }}" class="{{ request()->routeIs('admin.jobs.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Jobs
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.applications.index') }}" class="{{ request()->routeIs('admin.applications.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Applications
                                </a>
                            </li>
                        </ul>
                    </li>



                    <!-- Communications & Support -->
                    <li x-data="{ open: {{ request()->routeIs('admin.tickets.*') || request()->routeIs('admin.notifications.*') || request()->routeIs('admin.contact-submissions.*') || request()->routeIs('admin.email-templates.*') || request()->routeIs('admin.sms-templates.*') || request()->routeIs('admin.campaigns.*') || request()->routeIs('admin.contact-lists.*') || request()->routeIs('admin.contacts.*') || request()->routeIs('admin.communication-scripts.*') || request()->routeIs('admin.communication-channels.*') ? 'true' : 'false' }} }">
                        <button type="button" class="group w-full flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-300 hover:text-white hover:bg-brand-primary/50" @click="open = !open">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.186.06-2.1 1.023-2.1 2.207v4.286c0 1.137.847 2.1 1.98 2.193.34.027.68.052 1.02.072v3.091l3-3c.411.246.85.45 1.31.638m0-8.681c-.551.488-1.293.73-2.02.73-.728 0-1.469-.242-2.02-.73" />
                            </svg>
                            <span class="flex-1 text-left">Communications</span>
                            <svg class="h-5 w-5 shrink-0 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <ul x-show="open" class="mt-1 px-2 space-y-1" style="display: none;">
                            <li>
                                <a href="{{ route('admin.campaigns.index') }}" class="{{ request()->routeIs('admin.campaigns.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Campaigns
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.contact-lists.index') }}" class="{{ request()->routeIs('admin.contact-lists.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Contact Lists
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.contacts.index') }}" class="{{ request()->routeIs('admin.contacts.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    All Contacts
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.communication-scripts.index') }}" class="{{ request()->routeIs('admin.communication-scripts.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Scripts & Playbooks
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.communication-channels.index') }}" class="{{ request()->routeIs('admin.communication-channels.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Channels
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.contact-submissions.index') }}" class="{{ request()->routeIs('admin.contact-submissions.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Submissions
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.tickets.index') }}" class="{{ request()->routeIs('admin.tickets.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Support Tickets
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.notifications.index') }}" class="{{ request()->routeIs('admin.notifications.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Push Notifications
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.email-templates.index') }}" class="{{ request()->routeIs('admin.email-templates.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Email Templates
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.sms-templates.index') }}" class="{{ request()->routeIs('admin.sms-templates.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    SMS Templates
                                </a>
                            </li>
                        </ul>
                    </li>


                    <!-- Store -->
                    <!-- <li x-data="{ open: {{ request()->routeIs('admin.store.*') ? 'true' : 'false' }} }">
                        <button type="button" class="group w-full flex items-center gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-300 hover:text-white hover:bg-brand-primary/50" @click="open = !open">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                            </svg>
                            <span class="flex-1 text-left">Store</span>
                            <svg class="h-5 w-5 shrink-0 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <ul x-show="open" class="mt-1 px-2 space-y-1" style="display: none;">
                            <li>
                                <a href="{{ route('admin.store.index') }}" class="{{ request()->routeIs('admin.store.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold pl-11">
                                    Products
                                </a>
                            </li>
                        </ul>
                    </li> -->

                    <!-- Settings -->
                    <li>
                        <a href="{{ route('admin.settings.index') }}" 
                           class="{{ request()->routeIs('admin.settings.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </a>
                    </li>

                    <!-- SEO Manager -->
                    <li>
                        <a href="{{ route('admin.seo.index') }}" 
                           class="{{ request()->routeIs('admin.seo.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            SEO Manager
                        </a>
                    </li>

                    <!-- Rate Limiting -->
                    <li>
                        <a href="{{ route('admin.rate-limits.index') }}" 
                           class="{{ request()->routeIs('admin.rate-limits.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" />
                            </svg>
                            Rate Limiting
                        </a>
                    </li>

                    <!-- System -->
                    <li>
                        <a href="{{ route('admin.system.index') }}" 
                           class="{{ request()->routeIs('admin.system.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                            </svg>
                            System
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <div class="sticky bottom-0 mt-4 border-t border-white/10 bg-brand-secondary pt-4">
            <ul role="list" class="-mx-2 space-y-1">
                <li>
                    <a href="{{ route('admin.account.password.edit') }}"
                       class="{{ request()->routeIs('admin.account.password.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 00-5.834-1.01m0 0A3 3 0 105.25 8.25M9.916 4.24A3 3 0 0115.75 5.25M6.75 13.5h10.5A2.25 2.25 0 0119.5 15.75v3A2.25 2.25 0 0117.25 21h-8.5A2.25 2.25 0 016.5 18.75v-3A2.25 2.25 0 018.75 13.5z" />
                        </svg>
                        Change Admin Password
                    </a>
                </li>
                <li>
                    <a href="{{ url('/') }}" target="_blank" 
                       class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-300 hover:bg-brand-primary/50 hover:text-white">
                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        View Website
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>
