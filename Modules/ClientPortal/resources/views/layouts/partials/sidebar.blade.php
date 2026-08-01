<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-brand-secondary px-6 pb-4">
    <div class="flex h-16 shrink-0 items-center">
        <h2 class="text-white text-xl font-bold">Client Portal</h2>
    </div>
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('client.dashboard') }}" 
                           class="{{ request()->routeIs('client.dashboard') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            Dashboard
                        </a>
                    </li>

                    <div class="text-xs font-semibold leading-6 text-gray-400 mt-6 mb-2 uppercase tracking-wider">Project Management</div>

                    <!-- Projects -->
                    <li>
                        <a href="{{ route('client.projects.index') }}" 
                           class="{{ request()->routeIs('client.projects.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                            </svg>
                            My Projects
                        </a>
                    </li>

                    <div class="text-xs font-semibold leading-6 text-gray-400 mt-6 mb-2 uppercase tracking-wider">Services & Orders</div>

                    <!-- Browse Services -->
                    <li>
                        <a href="{{ route('client.services.index') }}" 
                           class="{{ request()->routeIs('client.services.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            Browse Services
                        </a>
                    </li>

                    <!-- My Orders -->
                    <li>
                        <a href="{{ route('client.orders.index') }}" 
                           class="{{ request()->routeIs('client.orders.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            My Orders
                        </a>
                    </li>

                    <div class="text-xs font-semibold leading-6 text-gray-400 mt-6 mb-2 uppercase tracking-wider">Payments & Billing</div>
                    
                    <!-- Quotes -->
                    <li>
                        <a href="{{ route('client.quotes.index') }}" 
                           class="{{ request()->routeIs('client.quotes.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Quotes
                        </a>
                    </li>
                    
                    <!-- Contracts -->
                    <li>
                        <a href="{{ route('client.contracts.index') }}" 
                           class="{{ request()->routeIs('client.contracts.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12" />
                            </svg>
                            Contracts
                        </a>
                    </li>

                    <!-- Transactions -->
                    <li>
                        <a href="{{ route('client.transactions.index') }}" 
                           class="{{ request()->routeIs('client.transactions.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                            </svg>
                            Transactions
                        </a>
                    </li>

                    <!-- Invoices -->
                    <li>
                        <a href="{{ route('client.invoices.index') }}" 
                           class="{{ request()->routeIs('client.invoices.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Invoices
                        </a>
                    </li>

                    <div class="text-xs font-semibold leading-6 text-gray-400 mt-6 mb-2 uppercase tracking-wider">Help & Support</div>

                    <!-- Support -->
                    <li>
                        <a href="{{ route('client.tickets.index') }}" 
                           class="{{ request()->routeIs('client.tickets.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                            </svg>
                            Support Tickets
                        </a>
                    </li>
                    <div class="text-xs font-semibold leading-6 text-gray-400 mt-6 mb-2 uppercase tracking-wider">Account Settings</div>

                    <!-- Profile -->
                     <li>
                        <a href="{{ route('client.profile.edit') }}" 
                           class="{{ request()->routeIs('client.profile.*') ? 'bg-brand-primary text-white' : 'text-gray-300 hover:text-white hover:bg-brand-primary/50' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            My Profile
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
