@extends('admin::layouts.app')

@section('title', 'Create SMS Template')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-white">Create SMS Template</h1>
        <p class="mt-1 text-sm text-gray-400">Design a new template for your SMS communications.</p>
    </div>
    <a href="{{ route('admin.sms-templates.index') }}" class="inline-flex items-center rounded-lg bg-[#10231C]/60 px-4 py-2 text-sm font-semibold text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-white/[0.03] transition-all">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        Back to Templates
    </a>
</div>

<div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <div class="overflow-hidden rounded-xl border border-white/10 bg-[#10231C]/60 border border-white/10">
            <form action="{{ route('admin.sms-templates.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-white leading-6">Template Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-2 block w-full rounded-lg border-0 py-2.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 bg-[#0a1612] text-white border-white/10" placeholder="e.g., Welcome Message" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="content" class="block text-sm font-semibold text-white leading-6">SMS Content</label>
                            <span id="charCount" class="text-xs font-medium text-gray-400">0 / 160 characters (1 SMS)</span>
                        </div>
                        <textarea name="content" id="content" rows="6" class="mt-2 block w-full rounded-lg border-0 py-2.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 bg-[#0a1612] text-white border-white/10" placeholder="Write your message here..." required>{{ old('content') }}</textarea>
                        <p class="mt-2 text-xs text-gray-400">You can use tags like @{{ first_name }}, @{{ last_name }}, @{{ company }} for personalization.</p>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-x-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-white/10 text-brand-primary focus:ring-brand-primary">
                        <label for="is_active" class="text-sm font-medium text-white">Active Template</label>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-x-4 border-t border-white/10 pt-6">
                    <button type="reset" class="text-sm font-semibold leading-6 text-white">Reset</button>
                    <button type="submit" class="rounded-lg bg-brand-primary px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary transition-all">Save Template</button>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="rounded-xl border border-white/10 bg-white/[0.03] p-6">
            <h2 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Personalization Tags</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm bg-[#10231C]/60 p-2.5 rounded-lg border border-white/10">
                    <code class="text-brand-primary">{&nbsp;{ first_name }&nbsp;}</code>
                    <span class="text-gray-400 text-xs">Contact's first name</span>
                </div>
                <div class="flex items-center justify-between text-sm bg-[#10231C]/60 p-2.5 rounded-lg border border-white/10">
                    <code class="text-brand-primary">{&nbsp;{ last_name }&nbsp;}</code>
                    <span class="text-gray-400 text-xs">Contact's last name</span>
                </div>
                <div class="flex items-center justify-between text-sm bg-[#10231C]/60 p-2.5 rounded-lg border border-white/10">
                    <code class="text-brand-primary">{&nbsp;{ email }&nbsp;}</code>
                    <span class="text-gray-400 text-xs">Contact's email</span>
                </div>
                <div class="flex items-center justify-between text-sm bg-[#10231C]/60 p-2.5 rounded-lg border border-white/10">
                    <code class="text-brand-primary">{&nbsp;{ company }&nbsp;}</code>
                    <span class="text-gray-400 text-xs">Contact's company</span>
                </div>
                <div class="flex items-center justify-between text-sm bg-[#10231C]/60 p-2.5 rounded-lg border border-white/10">
                    <code class="text-brand-primary">{&nbsp;{ phone }&nbsp;}</code>
                    <span class="text-gray-400 text-xs">Contact's phone number</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const contentArea = document.getElementById('content');
    const charCount = document.getElementById('charCount');

    function updateCharCount() {
        const length = contentArea.value.length;
        const smsCount = Math.ceil(length / 160) || 1;
        charCount.textContent = `${length} / 160 characters (${smsCount} SMS)`;
        
        if (length > 160) {
            charCount.classList.add('text-amber-600');
            charCount.classList.remove('text-gray-400');
        } else {
            charCount.classList.remove('text-amber-600');
            charCount.classList.add('text-gray-400');
        }
    }

    contentArea.addEventListener('input', updateCharCount);
    updateCharCount();
</script>
@endpush
