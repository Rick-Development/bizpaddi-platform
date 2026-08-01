@extends('teamportal::layouts.app')

@section('title', 'Account Status')

@section('content')
<div class="fixed inset-0 z-[100] bg-gray-50 flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="absolute inset-0 bg-gradient-to-br from-brand-secondary via-gray-900 to-black opacity-95"></div>
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
    
    <div class="relative max-w-lg w-full space-y-8 bg-white/10 backdrop-blur-xl p-10 rounded-3xl shadow-2xl border border-white/20">
        <div class="text-center">
            @if($status === 'suspended')
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 dark:bg-red-900/30 mb-6">
                    <svg class="h-10 w-10 text-red-600 dark:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-extrabold text-white mb-2">Account Suspended</h2>
            @else
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-yellow-500/20 mb-6">
                    <svg class="h-10 w-10 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-extrabold text-white mb-2">Account Inactive</h2>
            @endif
            
            <div class="mt-4 p-6 bg-white/5 rounded-2xl text-gray-200 text-base leading-relaxed border border-white/10 italic">
                {!! $message !!}
            </div>

            <div class="mt-8 flex flex-col space-y-4">
                <form action="{{ route('team.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-base font-bold text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-all duration-300 transform hover:scale-[1.02]">
                        Logout safely
                    </button>
                </form>
                
                <a href="mailto:{{ \Modules\Settings\Models\Setting::get('contact_email', 'support@rickdevelopment.com') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors duration-200">
                    Need help? Contact Support
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
