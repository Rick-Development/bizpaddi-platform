<div 
    x-data="notificationPrompt()" 
    x-show="showPrompt" 
    style="display: none;"
    class="relative z-50" 
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
>
    <!-- Background backdrop -->
    <div 
        x-show="showPrompt"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
    ></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal panel -->
            <div 
                x-show="showPrompt"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
            >
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-brand-primary/10 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Enable Notifications</h3>
                        <div class="mt-2">
                            <p x-show="permissionStatus === 'default'" class="text-sm text-gray-500">
                                Please enable browser notifications so you never miss a task assignment, project update, or important reminder.
                            </p>
                            <p x-show="permissionStatus === 'denied'" class="text-sm text-gray-500">
                                It looks like notifications are currently <strong>blocked</strong> in your browser. <br><br>
                                To enable them, please click the lock icon (🔒) or settings icon next to the URL address bar, change Notifications to "Allow", and refresh the page.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button x-show="permissionStatus === 'default'" @click="requestPermission" type="button" class="inline-flex w-full justify-center rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/80 sm:ml-3 sm:w-auto">
                        Enable Notifications
                    </button>
                    <button @click="showPrompt = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Remind Me Later
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notificationPrompt', () => ({
            showPrompt: false,
            permissionStatus: 'default',
            
            init() {
                // Check if browser supports notifications
                if (!('Notification' in window)) {
                    return;
                }
                
                this.permissionStatus = Notification.permission;
                
                // Show prompt if not granted
                if (this.permissionStatus !== 'granted') {
                    setTimeout(() => {
                        this.showPrompt = true;
                    }, 1000);
                }
            },
            
            requestPermission() {
                if (!('Notification' in window)) return;
                
                Notification.requestPermission().then((permission) => {
                    this.permissionStatus = permission;
                    if (permission === 'granted') {
                        this.showPrompt = false;
                        // FCM script will automatically initialize because it listens to permission changes or next page load
                        window.location.reload();
                    } else if (permission === 'denied') {
                        // User blocked it just now
                        // Leave the modal open so they see the instructions on how to unblock
                    }
                });
            },
            
            dismiss() {
                this.showPrompt = false;
                sessionStorage.setItem('notification_prompt_dismissed', 'true');
            }
        }))
    })
</script>
