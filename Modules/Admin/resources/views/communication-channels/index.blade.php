@extends('admin::layouts.app')

@section('title', 'Communication Channels')

@section('content')
<div class="communication-channels-page">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Communication Channels</h1>
            <p class="mt-2 text-sm text-gray-300">Manage the channels used for your communication scripts.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button @click="$dispatch('open-modal', 'create-channel-modal')" class="inline-flex items-center justify-center rounded-md border border-transparent bg-brand-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 sm:w-auto">
                Add Channel
            </button>
        </div>
    </div>

    <!-- Channels List -->
    <div class="bg-[#10231C]/60 border border-white/10 overflow-hidden sm:rounded-md">
        <ul role="list" class="divide-y divide-white/10">
            @forelse($channels as $channel)
            <li>
                <div class="px-4 py-4 sm:px-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-white/[0.06] rounded-full flex items-center justify-center text-gray-400">
                            @if($channel->icon)
                                <i class="{{ $channel->icon }}"></i>
                            @else
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-white">{{ $channel->name }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $channel->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $channel->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="$dispatch('open-modal', 'edit-channel-modal-{{ $channel->id }}')" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                        <form action="{{ route('admin.communication-channels.destroy', $channel->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                        </form>
                    </div>
                </div>
                
                <!-- Edit Modal - Enhanced -->
                <x-modal name="edit-channel-modal-{{ $channel->id }}">
                    <div class="bg-[#10231C]/60 rounded-2xl shadow-2xl overflow-hidden max-w-3xl mx-auto" x-data="{ editIcon: '{{ $channel->icon }}' }">
                        <!-- Header -->
                        <div class="relative px-6 py-5 bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-500">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-[#10231C]/60/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-white">Edit Channel</h3>
                                        <p class="text-sm text-white/90 mt-1">Update channel settings</p>
                                    </div>
                                </div>
                                <button type="button"
                                        @click="$dispatch('close-modal', 'edit-channel-modal-{{ $channel->id }}')" 
                                        class="w-9 h-9 rounded-lg bg-[#10231C]/60/10 hover:bg-[#10231C]/60/20 transition-all duration-200 flex items-center justify-center group">
                                    <svg class="w-5 h-5 text-white/80 group-hover:text-white group-hover:rotate-90 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <form action="{{ route('admin.communication-channels.update', $channel->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="px-6 py-6 space-y-6">
                                <!-- Name Field with Icon -->
                                <div class="group">
                                    <label for="name_{{ $channel->id }}" class="block text-sm font-semibold text-white mb-2">
                                        Channel Name <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               name="name" 
                                               id="name_{{ $channel->id }}" 
                                               value="{{ $channel->name }}" 
                                               class="w-full px-4 py-3.5 pl-12 border-2 border-white/10 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 text-white placeholder-gray-400" 
                                               placeholder="e.g. Cold Call, Email, WhatsApp"
                                               required>
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Icon Field with Preview -->
                                <div class="group">
                                    <label class="block text-sm font-semibold text-white mb-3">
                                        Channel Icon <span class="text-xs text-gray-400 font-normal">(Optional)</span>
                                    </label>
                                    
                                    <!-- Icon Preview & Input -->
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl p-4 border-2 border-white/10 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-14 h-14 rounded-xl bg-[#10231C]/60 border-2 border-white/10 flex items-center justify-center" x-show="!editIcon || editIcon === ''">
                                                    <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center shadow-lg" x-show="editIcon && editIcon !== ''">
                                                    <i :class="editIcon" class="text-2xl text-white"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <input type="text" 
                                                       name="icon" 
                                                       id="icon_{{ $channel->id }}" 
                                                       x-model="editIcon"
                                                       value="{{ $channel->icon }}" 
                                                       class="w-full px-4 py-3 border-2 border-white/10 rounded-lg focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 text-white placeholder-gray-400" 
                                                       placeholder="e.g. fas fa-phone, fab fa-whatsapp">
                                                <p class="mt-2 text-xs text-gray-400">Font Awesome class name</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Popular Icons Grid -->
                                    <div class="space-y-3">
                                        <p class="text-xs font-semibold text-gray-300 uppercase tracking-wide">Popular Channels</p>
                                        <div class="grid grid-cols-4 gap-3">
                                            <button 
                                                type="button" 
                                                @click="editIcon = 'fas fa-phone'"
                                                class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                :class="editIcon === 'fas fa-phone' ? 'border-indigo-500 bg-indigo-50' : ''"
                                            >
                                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-medium text-gray-300 group-hover:text-indigo-600 transition-colors">Phone Call</span>
                                            </button>

                                            <button 
                                                type="button" 
                                                @click="editIcon = 'fas fa-envelope'"
                                                class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                :class="editIcon === 'fas fa-envelope' ? 'border-indigo-500 bg-indigo-50' : ''"
                                            >
                                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-medium text-gray-300 group-hover:text-indigo-600 transition-colors">Email</span>
                                            </button>

                                            <button 
                                                type="button" 
                                                @click="editIcon = 'fab fa-whatsapp'"
                                                class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                :class="editIcon === 'fab fa-whatsapp' ? 'border-indigo-500 bg-indigo-50' : ''"
                                            >
                                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-medium text-gray-300 group-hover:text-indigo-600 transition-colors">WhatsApp</span>
                                            </button>

                                            <button 
                                                type="button" 
                                                @click="editIcon = 'fab fa-linkedin'"
                                                class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                :class="editIcon === 'fab fa-linkedin' ? 'border-indigo-500 bg-indigo-50' : ''"
                                            >
                                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-medium text-gray-300 group-hover:text-indigo-600 transition-colors">LinkedIn</span>
                                            </button>

                                            <button 
                                                type="button" 
                                                @click="editIcon = 'fab fa-telegram'"
                                                class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                :class="editIcon === 'fab fa-telegram' ? 'border-indigo-500 bg-indigo-50' : ''"
                                            >
                                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-sky-500 to-sky-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-medium text-gray-300 group-hover:text-indigo-600 transition-colors">Telegram</span>
                                            </button>

                                            <button 
                                                type="button" 
                                                @click="editIcon = 'fab fa-slack'"
                                                class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                :class="editIcon === 'fab fa-slack' ? 'border-indigo-500 bg-indigo-50' : ''"
                                            >
                                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M5.042 15.165a2.528 2.528 0 0 1-2.52 2.523A2.528 2.528 0 0 1 0 15.165a2.527 2.527 0 0 1 2.522-2.52h2.52v2.52zM6.313 15.165a2.527 2.527 0 0 1 2.521-2.52 2.527 2.527 0 0 1 2.521 2.52v6.313A2.528 2.528 0 0 1 8.834 24a2.528 2.528 0 0 1-2.521-2.522v-6.313zM8.834 5.042a2.528 2.528 0 0 1-2.521-2.52A2.528 2.528 0 0 1 8.834 0a2.528 2.528 0 0 1 2.521 2.522v2.52H8.834zM8.834 6.313a2.528 2.528 0 0 1 2.521 2.521 2.528 2.528 0 0 1-2.521 2.521H2.522A2.528 2.528 0 0 1 0 8.834a2.528 2.528 0 0 1 2.522-2.521h6.312zM18.956 8.834a2.528 2.528 0 0 1 2.522-2.521A2.528 2.528 0 0 1 24 8.834a2.528 2.528 0 0 1-2.522 2.521h-2.522V8.834zM17.688 8.834a2.528 2.528 0 0 1-2.523 2.521 2.527 2.527 0 0 1-2.52-2.521V2.522A2.527 2.527 0 0 1 15.165 0a2.528 2.528 0 0 1 2.523 2.522v6.312zM15.165 18.956a2.528 2.528 0 0 1 2.523 2.522A2.528 2.528 0 0 1 15.165 24a2.527 2.527 0 0 1-2.52-2.522v-2.522h2.52zM15.165 17.688a2.527 2.527 0 0 1-2.52-2.523 2.526 2.526 0 0 1 2.52-2.52h6.313A2.527 2.527 0 0 1 24 15.165a2.528 2.528 0 0 1-2.522 2.523h-6.313z"/>
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-medium text-gray-300 group-hover:text-indigo-600 transition-colors">Slack</span>
                                            </button>

                                            <button 
                                                type="button" 
                                                @click="editIcon = 'fab fa-twitter'"
                                                class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                :class="editIcon === 'fab fa-twitter' ? 'border-indigo-500 bg-indigo-50' : ''"
                                            >
                                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-medium text-gray-300 group-hover:text-indigo-600 transition-colors">Twitter/X</span>
                                            </button>

                                            <button 
                                                type="button" 
                                                @click="editIcon = 'fas fa-sms'"
                                                class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                                :class="editIcon === 'fas fa-sms' ? 'border-indigo-500 bg-indigo-50' : ''"
                                            >
                                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                                                    </svg>
                                                </div>
                                                <span class="text-xs font-medium text-gray-300 group-hover:text-indigo-600 transition-colors">SMS</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Active Toggle -->
                                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-4 border-2 border-indigo-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-[#10231C]/60 border-2 border-indigo-300 flex items-center justify-center shadow-sm">
                                                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <label for="is_active_{{ $channel->id }}" class="block text-sm font-bold text-white">
                                                    Active Status
                                                </label>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    Enable this channel for use in scripts
                                                </p>
                                            </div>
                                        </div>
                                        <div class="relative inline-flex items-center">
                                            <input type="checkbox" 
                                                   name="is_active" 
                                                   id="is_active_{{ $channel->id }}" 
                                                   value="1" 
                                                   {{ $channel->is_active ? 'checked' : '' }} 
                                                   class="sr-only peer">
                                            <label for="is_active_{{ $channel->id }}" 
                                                   class="w-12 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-[#10231C]/60 after:border-white/10 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-indigo-500 peer-checked:to-purple-500 cursor-pointer shadow-inner"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-gray-100/50 border-t-2 border-white/10 flex items-center justify-between">
                                <p class="text-xs text-gray-400 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Changes are saved securely
                                </p>
                                <div class="flex items-center gap-3">
                                    <button type="button" 
                                            @click="$dispatch('close-modal', 'edit-channel-modal-{{ $channel->id }}')" 
                                            class="px-5 py-2.5 rounded-xl border-2 border-white/10 bg-[#10231C]/60 text-sm font-semibold text-gray-300 hover:bg-white/[0.03] hover:border-gray-400 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-gray-200">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-500 text-sm font-semibold text-white hover:shadow-xl hover:shadow-indigo-500/30 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 flex items-center gap-2 hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Update Channel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </x-modal>
            </li>
            @empty
            <li class="px-4 py-8 text-center text-gray-400">
                No channels found. create one to get started.
            </li>
            @endforelse
        </ul>
    </div>

    <!-- Create Modal - Enhanced -->
    <x-modal name="create-channel-modal">
        <div class="bg-[#10231C]/60 rounded-2xl shadow-2xl overflow-hidden max-w-3xl mx-auto" x-data="{ selectedIcon: '', previewIcon: '' }">
            <!-- Header -->
            <div class="relative px-6 py-5 bg-gradient-to-r from-emerald-600 via-emerald-500 to-teal-500">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-[#10231C]/60/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Add Communication Channel</h3>
                            <p class="text-sm text-white/90 mt-1">Configure a new channel for your scripts</p>
                        </div>
                    </div>
                    <button type="button"
                            @click="$dispatch('close-modal', 'create-channel-modal')" 
                            class="w-9 h-9 rounded-lg bg-[#10231C]/60/10 hover:bg-[#10231C]/60/20 transition-all duration-200 flex items-center justify-center group">
                        <svg class="w-5 h-5 text-white/80 group-hover:text-white group-hover:rotate-90 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <form action="{{ route('admin.communication-channels.store') }}" method="POST">
                @csrf
                
                <div class="px-6 py-6 space-y-6">
                    <!-- Channel Name with Live Preview -->
                    <div class="group">
                        <label for="new_name" class="block text-sm font-semibold text-white mb-2">
                            Channel Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="name" 
                                id="new_name" 
                                class="w-full px-4 py-3.5 pl-12 border-2 border-white/10 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all duration-200 text-white placeholder-gray-400 bg-[#0a1612] text-white border-white/10" 
                                required 
                                placeholder="e.g., Cold Call, WhatsApp, LinkedIn DM"
                                autocomplete="off"
                            >
                            <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Choose a clear, descriptive name for this communication channel
                        </p>
                    </div>

                    <!-- Icon Selection - Enhanced -->
                    <div class="group">
                        <label class="block text-sm font-semibold text-white mb-3">
                            Channel Icon <span class="text-xs text-gray-400 font-normal">(Optional)</span>
                        </label>
                        
                        <!-- Icon Preview & Input -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl p-4 border-2 border-white/10 mb-4">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 rounded-xl bg-[#10231C]/60 border-2 border-white/10 flex items-center justify-center" x-show="!selectedIcon">
                                        <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-lg" x-show="selectedIcon">
                                        <i :class="selectedIcon" class="text-2xl text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <input 
                                        type="text" 
                                        name="icon" 
                                        id="new_icon" 
                                        x-model="selectedIcon"
                                        class="w-full px-4 py-3 border-2 border-white/10 rounded-lg focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all duration-200 text-white placeholder-gray-400 bg-[#0a1612] text-white border-white/10" 
                                        placeholder="e.g., fas fa-phone, fab fa-whatsapp"
                                        autocomplete="off"
                                    >
                                    <p class="mt-2 text-xs text-gray-400">Font Awesome class name</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Popular Icons Grid -->
                        <div class="space-y-3">
                            <p class="text-xs font-semibold text-gray-300 uppercase tracking-wide">Popular Channels</p>
                            <div class="grid grid-cols-4 gap-3">
                                <button 
                                    type="button" 
                                    @click="selectedIcon = 'fas fa-phone'"
                                    class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                    :class="selectedIcon === 'fas fa-phone' ? 'border-emerald-500 bg-emerald-50' : ''"
                                >
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-300 group-hover:text-emerald-600 transition-colors">Phone Call</span>
                                </button>

                                <button 
                                    type="button" 
                                    @click="selectedIcon = 'fas fa-envelope'"
                                    class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                    :class="selectedIcon === 'fas fa-envelope' ? 'border-emerald-500 bg-emerald-50' : ''"
                                >
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-300 group-hover:text-emerald-600 transition-colors">Email</span>
                                </button>

                                <button 
                                    type="button" 
                                    @click="selectedIcon = 'fab fa-whatsapp'"
                                    class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                    :class="selectedIcon === 'fab fa-whatsapp' ? 'border-emerald-500 bg-emerald-50' : ''"
                                >
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-300 group-hover:text-emerald-600 transition-colors">WhatsApp</span>
                                </button>

                                <button 
                                    type="button" 
                                    @click="selectedIcon = 'fab fa-linkedin'"
                                    class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                    :class="selectedIcon === 'fab fa-linkedin' ? 'border-emerald-500 bg-emerald-50' : ''"
                                >
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-300 group-hover:text-emerald-600 transition-colors">LinkedIn</span>
                                </button>

                                <button 
                                    type="button" 
                                    @click="selectedIcon = 'fab fa-telegram'"
                                    class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                    :class="selectedIcon === 'fab fa-telegram' ? 'border-emerald-500 bg-emerald-50' : ''"
                                >
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-sky-500 to-sky-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-300 group-hover:text-emerald-600 transition-colors">Telegram</span>
                                </button>

                                <button 
                                    type="button" 
                                    @click="selectedIcon = 'fab fa-slack'"
                                    class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                    :class="selectedIcon === 'fab fa-slack' ? 'border-emerald-500 bg-emerald-50' : ''"
                                >
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M5.042 15.165a2.528 2.528 0 0 1-2.52 2.523A2.528 2.528 0 0 1 0 15.165a2.527 2.527 0 0 1 2.522-2.52h2.52v2.52zM6.313 15.165a2.527 2.527 0 0 1 2.521-2.52 2.527 2.527 0 0 1 2.521 2.52v6.313A2.528 2.528 0 0 1 8.834 24a2.528 2.528 0 0 1-2.521-2.522v-6.313zM8.834 5.042a2.528 2.528 0 0 1-2.521-2.52A2.528 2.528 0 0 1 8.834 0a2.528 2.528 0 0 1 2.521 2.522v2.52H8.834zM8.834 6.313a2.528 2.528 0 0 1 2.521 2.521 2.528 2.528 0 0 1-2.521 2.521H2.522A2.528 2.528 0 0 1 0 8.834a2.528 2.528 0 0 1 2.522-2.521h6.312zM18.956 8.834a2.528 2.528 0 0 1 2.522-2.521A2.528 2.528 0 0 1 24 8.834a2.528 2.528 0 0 1-2.522 2.521h-2.522V8.834zM17.688 8.834a2.528 2.528 0 0 1-2.523 2.521 2.527 2.527 0 0 1-2.52-2.521V2.522A2.527 2.527 0 0 1 15.165 0a2.528 2.528 0 0 1 2.523 2.522v6.312zM15.165 18.956a2.528 2.528 0 0 1 2.523 2.522A2.528 2.528 0 0 1 15.165 24a2.527 2.527 0 0 1-2.52-2.522v-2.522h2.52zM15.165 17.688a2.527 2.527 0 0 1-2.52-2.523 2.526 2.526 0 0 1 2.52-2.52h6.313A2.527 2.527 0 0 1 24 15.165a2.528 2.528 0 0 1-2.522 2.523h-6.313z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-300 group-hover:text-emerald-600 transition-colors">Slack</span>
                                </button>

                                <button 
                                    type="button" 
                                    @click="selectedIcon = 'fab fa-twitter'"
                                    class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                    :class="selectedIcon === 'fab fa-twitter' ? 'border-emerald-500 bg-emerald-50' : ''"
                                >
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-300 group-hover:text-emerald-600 transition-colors">Twitter/X</span>
                                </button>

                                <button 
                                    type="button" 
                                    @click="selectedIcon = 'fas fa-sms'"
                                    class="group relative flex flex-col items-center justify-center p-4 border-2 border-white/10 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
                                    :class="selectedIcon === 'fas fa-sms' ? 'border-emerald-500 bg-emerald-50' : ''"
                                >
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-200">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-300 group-hover:text-emerald-600 transition-colors">SMS</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-4 border-2 border-emerald-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-[#10231C]/60 border-2 border-emerald-300 flex items-center justify-center shadow-sm">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <label for="new_is_active" class="block text-sm font-bold text-white">
                                        Active Status
                                    </label>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Make this channel immediately available for use in scripts
                                    </p>
                                </div>
                            </div>
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="new_is_active" 
                                       value="1" 
                                       checked 
                                       class="sr-only peer">
                                <label for="new_is_active" 
                                       class="w-12 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-[#10231C]/60 after:border-white/10 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-emerald-500 peer-checked:to-emerald-600 cursor-pointer shadow-inner"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-gray-100/50 border-t-2 border-white/10 flex items-center justify-between">
                    <p class="text-xs text-gray-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        All fields are securely saved
                    </p>
                    <div class="flex items-center gap-3">
                        <button 
                            type="button" 
                            @click="$dispatch('close-modal', 'create-channel-modal')" 
                            class="px-5 py-2.5 rounded-xl border-2 border-white/10 bg-[#10231C]/60 text-sm font-semibold text-gray-300 hover:bg-white/[0.03] hover:border-gray-400 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-gray-200">
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 via-emerald-500 to-teal-500 text-sm font-semibold text-white hover:shadow-xl hover:shadow-emerald-500/30 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 flex items-center gap-2 hover:scale-105">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Create Channel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>
</div>
@endsection