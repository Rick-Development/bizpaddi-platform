@extends('clientportal::layouts.app')

@section('title', 'New Support Ticket')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('client.tickets.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">New Support Ticket</h1>
            <p class="mt-1 text-sm text-gray-600">Describe your issue and we'll get back to you shortly</p>
        </div>
    </div>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('client.tickets.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Subject -->
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                <div class="mt-1">
                    <input type="text" name="subject" id="subject" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-xl px-4 py-3" placeholder="Brief summary of the issue" required>
                </div>
            </div>

            <!-- Priority -->
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                <div class="mt-1">
                    <select id="priority" name="priority" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-xl px-4 py-3">
                        <option value="low">Low - General inquiry, no rush</option>
                        <option value="medium" selected>Medium - Standard issue requiring attention</option>
                        <option value="high">High - Urgent issue blocking work</option>
                        <option value="urgent">Urgent - System down / Critical failure</option>
                    </select>
                </div>
            </div>

            <!-- Message -->
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                <div class="mt-1">
                    <textarea id="message" name="message" rows="6" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-xl px-4 py-3" placeholder="Please provide detailed information about your request..." required></textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-x-4 pt-4 border-t border-gray-100">
                <a href="{{ route('client.tickets.index') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Cancel</a>
                <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary shadow-brand-primary/30 hover:-translate-y-0.5 transition-all">
                    Submit Ticket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
