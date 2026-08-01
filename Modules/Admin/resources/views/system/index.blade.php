@extends('admin::layouts.app')

@section('title', 'System Maintenance')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">System Maintenance</h1>
        <p class="mt-1 text-sm text-gray-600">Run system optimization and maintenance commands (php artisan).</p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-md bg-red-50 p-4 border border-red-200">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Optimize -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Optimize</h3>
                <p class="text-sm text-gray-600 mb-4">Cache configuration, routes, and services for better performance.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST">
                @csrf
                <input type="hidden" name="command" value="optimize">
                <button type="submit" class="w-full px-4 py-2 bg-brand-primary text-white rounded-md text-sm font-medium hover:bg-brand-primary/90 transition-colors">
                    Run Optimize
                </button>
            </form>
        </div>

        <!-- Optimize Clear -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Clear Optimization</h3>
                <p class="text-sm text-gray-600 mb-4">Remove all cached files (config, route, and services).</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST">
                @csrf
                <input type="hidden" name="command" value="optimize-clear">
                <button type="submit" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                    Clear Optimization
                </button>
            </form>
        </div>

        <!-- Cache Clear -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Application Cache</h3>
                <p class="text-sm text-gray-600 mb-4">Flush the application cache.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST">
                @csrf
                <input type="hidden" name="command" value="cache-clear">
                <button type="submit" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                    Clear App Cache
                </button>
            </form>
        </div>

        <!-- View Clear -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Clear Views</h3>
                <p class="text-sm text-gray-600 mb-4">Clear all compiled view files.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST">
                @csrf
                <input type="hidden" name="command" value="view-clear">
                <button type="submit" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                    Clear Views
                </button>
            </form>
        </div>

        <!-- Config Clear -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Clear Config</h3>
                <p class="text-sm text-gray-600 mb-4">Remove the configuration cache file.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST">
                @csrf
                <input type="hidden" name="command" value="config-clear">
                <button type="submit" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                    Clear Config
                </button>
            </form>
        </div>

        <!-- Route Clear -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Clear Routes</h3>
                <p class="text-sm text-gray-600 mb-4">Remove the route cache file.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST">
                @csrf
                <input type="hidden" name="command" value="route-clear">
                <button type="submit" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                    Clear Routes
                </button>
            </form>
        </div>

        <!-- Storage Link -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Storage Link</h3>
                <p class="text-sm text-gray-600 mb-4">Create the symbolic link from public/storage to storage/app/public.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST">
                @csrf
                <input type="hidden" name="command" value="storage-link">
                <button type="submit" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                    Link Storage
                </button>
            </form>
        </div>

        <!-- Run Migrations -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Run Migrations</h3>
                <p class="text-sm text-gray-600 mb-4">Execute pending database migrations.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST">
                @csrf
                <input type="hidden" name="command" value="migrate">
                <button type="submit" class="w-full px-4 py-2 border border-brand-primary text-brand-primary rounded-md text-sm font-medium hover:bg-brand-primary/5 transition-colors">
                    Run Migrations
                </button>
            </form>
        </div>

        <!-- DB Seeding -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">DB Seeding</h3>
                <p class="text-sm text-gray-600 mb-4">Run the main database seeders.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST" onsubmit="return confirm('Run standard seeders?')">
                @csrf
                <input type="hidden" name="command" value="db-seed">
                <button type="submit" class="w-full px-4 py-2 border border-yellow-300 text-yellow-700 rounded-md text-sm font-medium hover:bg-yellow-50 transition-colors">
                    Seed Database
                </button>
            </form>
        </div>

        <!-- Composer Dump -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Composer Dump</h3>
                <p class="text-sm text-gray-600 mb-4">Regenerate the list of all classes that need to be included.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST">
                @csrf
                <input type="hidden" name="command" value="composer-dump">
                <button type="submit" class="w-full px-4 py-2 bg-brand-secondary text-white rounded-md text-sm font-medium hover:bg-brand-secondary/90 transition-colors">
                    Dump Autoload
                </button>
            </form>
        </div>
    </div>

    <!-- Queue Management Section -->
    <div class="mt-12 mb-6">
        <h2 class="text-xl font-bold text-gray-900">Queue Management</h2>
        <p class="mt-1 text-sm text-gray-600">Control and manage your application queues and background jobs.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Queue Restart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Queue Restart</h3>
                <p class="text-sm text-gray-600 mb-4">Restart queue worker daemons.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST">
                @csrf
                <input type="hidden" name="command" value="queue-restart">
                <button type="submit" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                    Restart Queues
                </button>
            </form>
        </div>

        <!-- Queue Clear -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Clear Queue</h3>
                <p class="text-sm text-gray-600 mb-4">Delete all jobs from the specified queue.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST" onsubmit="return confirm('Danger! This will delete all pending jobs. Continue?')">
                @csrf
                <input type="hidden" name="command" value="queue-clear">
                <button type="submit" class="w-full px-4 py-2 border border-red-300 text-red-700 rounded-md text-sm font-medium hover:bg-red-50 transition-colors">
                    Clear All Jobs
                </button>
            </form>
        </div>

        <!-- Retry All -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Retry Failed Jobs</h3>
                <p class="text-sm text-gray-600 mb-4">Retry all of the failed queue jobs.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST">
                @csrf
                <input type="hidden" name="command" value="queue-retry-all">
                <button type="submit" class="w-full px-4 py-2 border border-brand-primary text-brand-primary rounded-md text-sm font-medium hover:bg-brand-primary/5 transition-colors">
                    Retry All Failed
                </button>
            </form>
        </div>

        <!-- Flush Failed -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Flush Failed</h3>
                <p class="text-sm text-gray-600 mb-4">Flush all of the failed queue jobs.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST" onsubmit="return confirm('Clear all failed jobs permanently?')">
                @csrf
                <input type="hidden" name="command" value="queue-flush">
                <button type="submit" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                    Flush Failed
                </button>
            </form>
        </div>

        <!-- Pause/Resume -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Status Control</h3>
                <p class="text-sm text-gray-600 mb-4">Pause or resume the queue workers.</p>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('admin.system.run') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="command" value="queue-pause">
                    <button type="submit" class="w-full px-3 py-2 border border-yellow-300 text-yellow-700 rounded-md text-xs font-medium hover:bg-yellow-50 transition-colors">
                        Pause
                    </button>
                </form>
                <form action="{{ route('admin.system.run') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="command" value="queue-resume">
                    <button type="submit" class="w-full px-3 py-2 border border-green-300 text-green-700 rounded-md text-xs font-medium hover:bg-green-50 transition-colors">
                        Resume
                    </button>
                </form>
            </div>
        </div>

        <!-- Pruning -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Prune Jobs</h3>
                <p class="text-sm text-gray-600 mb-4">Prune stale batches and failed jobs.</p>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('admin.system.run') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="command" value="queue-prune-batches">
                    <button type="submit" class="w-full px-3 py-2 border border-gray-300 text-gray-700 rounded-md text-xs font-medium hover:bg-gray-50 transition-colors">
                        Batches
                    </button>
                </form>
                <form action="{{ route('admin.system.run') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="command" value="queue-prune-failed">
                    <button type="submit" class="w-full px-3 py-2 border border-gray-300 text-gray-700 rounded-md text-xs font-medium hover:bg-gray-50 transition-colors">
                        Failed
                    </button>
                </form>
            </div>
        </div>

        <!-- Database Tables -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Setup Tables</h3>
                <p class="text-sm text-gray-600 mb-4">Create migrations for queue database tables.</p>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <form action="{{ route('admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="queue-table">
                    <button type="submit" class="w-full px-2 py-1.5 border border-gray-300 text-gray-600 rounded text-[10px] font-medium hover:bg-gray-50 uppercase tracking-wider">
                        Main
                    </button>
                </form>
                <form action="{{ route('admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="queue-failed-table">
                    <button type="submit" class="w-full px-2 py-1.5 border border-gray-300 text-gray-600 rounded text-[10px] font-medium hover:bg-gray-50 uppercase tracking-wider">
                        Failed
                    </button>
                </form>
                <form action="{{ route('admin.system.run') }}" method="POST" class="col-span-2">
                    @csrf
                    <input type="hidden" name="command" value="queue-batches-table">
                    <button type="submit" class="w-full px-2 py-1.5 border border-gray-300 text-gray-600 rounded text-[10px] font-medium hover:bg-gray-50 uppercase tracking-wider">
                        Batches Table
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Permission Cache Reset -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Reset Permissions</h3>
                <p class="text-sm text-gray-600 mb-4">Clear the cached permissions (Spatie).</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST">
                @csrf
                <input type="hidden" name="command" value="permission-cache-reset">
                <button type="submit" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                    Reset Permissions
                </button>
            </form>
        </div>

        <!-- Event Management -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Event Cache</h3>
                <p class="text-sm text-gray-600 mb-4">Cache or clear application events.</p>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('admin.system.run') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="command" value="event-cache">
                    <button type="submit" class="w-full px-3 py-2 border border-brand-primary text-brand-primary rounded-md text-xs font-medium hover:bg-brand-primary/5 transition-colors">
                        Cache
                    </button>
                </form>
                <form action="{{ route('admin.system.run') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="command" value="event-clear">
                    <button type="submit" class="w-full px-3 py-2 border border-gray-300 text-gray-700 rounded-md text-xs font-medium hover:bg-gray-50 transition-colors">
                        Clear
                    </button>
                </form>
            </div>
        </div>

        <!-- Module Migrations -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Module Migrations</h3>
                <p class="text-sm text-gray-600 mb-4">Run migrations for all active modules.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST">
                @csrf
                <input type="hidden" name="command" value="module-migrate">
                <button type="submit" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                    Migrate Modules
                </button>
            </form>
        </div>

        <!-- Module Seeding -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Module Seeding</h3>
                <p class="text-sm text-gray-600 mb-4">Run seeders for all active modules.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST" onsubmit="return confirm('Are you sure? This might duplicate data.')">
                @csrf
                <input type="hidden" name="command" value="module-seed">
                <button type="submit" class="w-full px-4 py-2 border border-yellow-300 text-yellow-700 rounded-md text-sm font-medium hover:bg-yellow-50 transition-colors">
                    Seed Modules
                </button>
            </form>
        </div>

        <!-- Media Library Clean -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Media Cleanup</h3>
                <p class="text-sm text-gray-600 mb-4">Remove unused media library files.</p>
            </div>
            <form action="{{ route('admin.system.run') }}" method="POST">
                @csrf
                <input type="hidden" name="command" value="medialibrary-clean">
                <button type="submit" class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition-colors">
                    Clean Media
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
