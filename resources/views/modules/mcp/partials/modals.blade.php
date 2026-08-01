<!-- Blog Post Modal -->
<div id="blogModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity z-50" onclick="if(event.target === this) closeModal('blogModal')">
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-3 sm:p-4 text-center sm:items-center">
            <div class="relative transform overflow-hidden rounded-xl bg-white px-4 pb-4 pt-5 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6" onclick="event.stopPropagation()">
                <!-- Close button -->
                <button type="button" onclick="closeModal('blogModal')" class="absolute right-4 top-4 text-gray-400 hover:text-gray-500 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <form action="{{ route('admin.mcp.execute') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tool" value="blog_create_post">
                    
                    <div>
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg">
                            <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </div>
                        <div class="mt-4 text-center sm:mt-5">
                            <h3 class="text-lg font-bold leading-6 text-gray-900">Create Blog Post</h3>
                            <p class="mt-1 text-sm text-gray-500">Fill in the details below or use AI chat for automatic generation</p>
                            <div class="mt-5 space-y-4">
                                <div class="text-left">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" required placeholder="Enter post title" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors">
                                </div>
                                <div class="text-left">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Content <span class="text-red-500">*</span></label>
                                    <textarea name="content" rows="4" required placeholder="Write your blog content here..." class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors"></textarea>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="text-left">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Category ID <span class="text-red-500">*</span></label>
                                        <input type="number" name="category_id" required value="1" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors">
                                    </div>
                                    <div class="text-left">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select name="status" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors">
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="text-left">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                                    <input type="text" name="tags" placeholder="laravel, php, tutorial" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors">
                                    <p class="mt-1 text-xs text-gray-500">Separate tags with commas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button type="button" onclick="closeModal('blogModal')" class="inline-flex justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Cancel</button>
                        <button type="submit" class="inline-flex justify-center rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-blue-500 hover:to-blue-600 transition-all">Create Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Task Assignment Modal -->
<div id="taskModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity z-50" onclick="if(event.target === this) closeModal('taskModal')">
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-3 sm:p-4 text-center sm:items-center">
            <div class="relative transform overflow-hidden rounded-xl bg-white px-4 pb-4 pt-5 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6" onclick="event.stopPropagation()">
                <button type="button" onclick="closeModal('taskModal')" class="absolute right-4 top-4 text-gray-400 hover:text-gray-500 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <form action="{{ route('admin.mcp.execute') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tool" value="pm_assign_task">
                    
                    <div>
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-purple-500 to-purple-600 shadow-lg">
                            <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                        </div>
                        <div class="mt-4 text-center sm:mt-5">
                            <h3 class="text-lg font-bold leading-6 text-gray-900">Smart Task Assignment</h3>
                            <p class="mt-1 text-sm text-gray-500">Task will be assigned to the developer with the lowest workload</p>
                            <div class="mt-5 space-y-4">
                                <div class="text-left">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Project ID <span class="text-red-500">*</span></label>
                                    <input type="number" name="project_id" required value="1" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm transition-colors">
                                </div>
                                <div class="text-left">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Task Title <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" required placeholder="Enter task title" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm transition-colors">
                                </div>
                                <div class="text-left">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea name="description" rows="3" placeholder="Describe the task..." class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm transition-colors"></textarea>
                                </div>
                                <div class="text-left">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Hours</label>
                                    <input type="number" name="estimated_hours" step="0.5" value="4" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 text-sm transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button type="button" onclick="closeModal('taskModal')" class="inline-flex justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Cancel</button>
                        <button type="submit" class="inline-flex justify-center rounded-lg bg-gradient-to-r from-purple-600 to-purple-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-purple-500 hover:to-purple-600 transition-all">Assign Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Modal -->
<div id="invoiceModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity z-50" onclick="if(event.target === this) closeModal('invoiceModal')">
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-3 sm:p-4 text-center sm:items-center">
            <div class="relative transform overflow-hidden rounded-xl bg-white px-4 pb-4 pt-5 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6" onclick="event.stopPropagation()">
                <button type="button" onclick="closeModal('invoiceModal')" class="absolute right-4 top-4 text-gray-400 hover:text-gray-500 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <form action="{{ route('admin.mcp.execute') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tool" value="finance_create_invoice">
                    
                    <div>
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-green-600 shadow-lg">
                            <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div class="mt-4 text-center sm:mt-5">
                            <h3 class="text-lg font-bold leading-6 text-gray-900">Create Invoice</h3>
                            <p class="mt-1 text-sm text-gray-500">Generate a new invoice for a client</p>
                            <div class="mt-5 space-y-4">
                                <div class="text-left">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Client ID <span class="text-red-500">*</span></label>
                                    <input type="number" name="client_id" required placeholder="Enter client ID" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm transition-colors">
                                </div>
                                <div class="text-left">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                                    <input type="date" name="due_date" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm transition-colors">
                                </div>
                                <div class="text-left">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Items (JSON) <span class="text-red-500">*</span></label>
                                    <textarea name="items" rows="3" required placeholder='[{"description": "Service A", "amount": 100, "quantity": 1}]' class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-xs font-mono transition-colors"></textarea>
                                    <p class="mt-1.5 text-xs text-gray-500 bg-gray-50 p-2 rounded">Format: [{"description": "...", "amount": 100, "quantity": 1}]</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button type="button" onclick="closeModal('invoiceModal')" class="inline-flex justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Cancel</button>
                        <button type="submit" class="inline-flex justify-center rounded-lg bg-gradient-to-r from-green-600 to-green-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-green-500 hover:to-green-600 transition-all">Create Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quote Modal -->
<div id="quoteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity z-50" onclick="if(event.target === this) closeModal('quoteModal')">
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-3 sm:p-4 text-center sm:items-center">
            <div class="relative transform overflow-hidden rounded-xl bg-white px-4 pb-4 pt-5 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6" onclick="event.stopPropagation()">
                <button type="button" onclick="closeModal('quoteModal')" class="absolute right-4 top-4 text-gray-400 hover:text-gray-500 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <form action="{{ route('admin.mcp.execute') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tool" value="finance_create_quote">
                    
                    <div>
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-amber-500 to-amber-600 shadow-lg">
                            <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.25-1.5c.578.437 1.125.925 1.625 1.458" />
                            </svg>
                        </div>
                        <div class="mt-4 text-center sm:mt-5">
                            <h3 class="text-lg font-bold leading-6 text-gray-900">Create Quote</h3>
                            <p class="mt-1 text-sm text-gray-500">Generate a new quote for a client</p>
                            <div class="mt-5 space-y-4">
                                <div class="text-left">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Client ID <span class="text-red-500">*</span></label>
                                    <input type="number" name="client_id" required placeholder="Enter client ID" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm transition-colors">
                                </div>
                                <div class="text-left">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Valid Until</label>
                                    <input type="date" name="valid_until" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm transition-colors">
                                </div>
                                <div class="text-left">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Items (JSON) <span class="text-red-500">*</span></label>
                                    <textarea name="items" rows="3" required placeholder='[{"description": "Service A", "amount": 100, "quantity": 1}]' class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-xs font-mono transition-colors"></textarea>
                                    <p class="mt-1.5 text-xs text-gray-500 bg-gray-50 p-2 rounded">Format: [{"description": "...", "amount": 100, "quantity": 1}]</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button type="button" onclick="closeModal('quoteModal')" class="inline-flex justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Cancel</button>
                        <button type="submit" class="inline-flex justify-center rounded-lg bg-gradient-to-r from-amber-600 to-amber-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-amber-500 hover:to-amber-600 transition-all">Create Quote</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
