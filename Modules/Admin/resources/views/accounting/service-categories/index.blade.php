@extends('admin::layouts.app')

@section('title', 'Service Categories')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Service Categories</h1>
            <p class="mt-1 text-sm text-gray-600">Organize your services into categories</p>
        </div>
        <a href="{{ route('admin.accounting.service-categories.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors shadow-sm">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Category
        </a>
    </div>
</div>

@if($categories->isEmpty())
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
    </svg>
    <h3 class="mt-4 text-lg font-semibold text-gray-900">No categories yet</h3>
    <p class="mt-2 text-sm text-gray-600">Get started by creating your first service category.</p>
    <div class="mt-6">
        <a href="{{ route('admin.accounting.service-categories.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create Category
        </a>
    </div>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($categories as $category)
    <div class="group relative flex flex-col bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
        <!-- Card Header with Color Gradient -->
        <div class="h-24 w-full relative overflow-hidden" style="background: linear-gradient(135deg, {{ $category->color }}15 0%, {{ $category->color }}05 100%);">
            <div class="absolute top-4 right-4">
                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-white/80 backdrop-blur-sm shadow-sm">
                   <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3.228-9.941a.75.75 0 011.05-.28l2.5 1.76a.75.75 0 01-.01 1.258l-2.49 1.558a.75.75 0 01-1.077-.92l.745-1.428-1.05-.595a.75.75 0 01.332-1.353zM18.8 12.8a.75.75 0 01-.659 1.348l-1.396-.684.58 1.492a.75.75 0 01-1.348.658l-.684-1.396-1.492.58a.75.75 0 01-.658-1.348l.684-1.396-.58-1.492a.75.75 0 011.348-.658l1.396.684-.58-1.492a.75.75 0 011.348-.658l.684 1.396 1.492-.58a.75.75 0 01.658 1.348l-.684 1.396 1.492.58z" />
                   </svg>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="flex-1 p-6 pt-0 relative">
             <!-- Icon Circle -->
            <div class="relative -mt-12 mb-4">
                <div class="h-20 w-20 rounded-2xl flex items-center justify-center shadow-lg transform group-hover:scale-105 transition-transform duration-300 bg-white p-1">
                    <div class="h-full w-full rounded-xl flex items-center justify-center" style="background-color: {{ $category->color }};">
                        <span class="text-3xl font-bold text-white">{{ substr($category->name, 0, 1) }}</span>
                    </div>
                </div>
            </div>

            <!-- Title & Stats -->
            <div class="mb-4">
                <h3 class="text-xl font-bold text-gray-900 leading-tight mb-1 group-hover:text-brand-primary transition-colors">{{ $category->name }}</h3>
                <p class="text-sm font-medium text-gray-500 flex items-center gap-2">
                    <span class="inline-block w-2 H-2 rounded-full" style="background-color: {{ $category->color }}"></span>
                    {{ $category->services_count }} Services
                </p>
            </div>

            <!-- Description -->
            <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed mb-6 h-10">
                {{ $category->description ?? 'No description provided for this category.' }}
            </p>

            <!-- Actions -->
            <div class="flex items-center gap-3 pt-4 border-t border-gray-50">
                <a href="{{ route('admin.accounting.service-categories.edit', $category) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-50 rounded-xl hover:bg-brand-primary hover:text-white transition-all duration-200 hover:shadow-md">
                    Edit
                </a>
                <form action="{{ route('admin.accounting.service-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');" class="flex-shrink-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex justify-center items-center p-2.5 text-gray-400 bg-white border border-gray-200 rounded-xl hover:bg-red-50 hover:border-red-100 hover:text-red-600 transition-all duration-200" title="Delete Category">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
