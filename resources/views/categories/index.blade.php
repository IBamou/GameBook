@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header">
            <div class="section-title">
                <h1>Categories</h1>
                <p>Browse games by category</p>
            </div>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('categories.create') }}" class="btn-primary">Add Category</a>
                @endif
            @endauth
        </div>

        <form method="GET" class="mb-6 flex gap-3 items-center">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..." 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            </div>
            <button type="submit" class="btn-secondary">Search</button>
            @if(request('search'))
                <a href="{{ route('categories.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Clear</a>
            @endif
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="card-hover group p-6 flex flex-col">
                    <h3 class="text-xl font-semibold text-slate-950 group-hover:text-sky-600 transition-colors">{{ $category->name }}</h3>
                    <p class="mt-3 text-slate-600 text-sm line-clamp-3">{{ $category->description }}</p>
                    <div class="mt-auto pt-4 flex items-center justify-between text-sm text-slate-500">
                        <span>{{ $category->games->count() }} games</span>
                        <span class="group-hover:translate-x-1 transition-transform">→</span>
                    </div>
                </a>
            @empty
                <div class="empty-state col-span-full">
                    <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="empty-state-text">No categories yet</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
