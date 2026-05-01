@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-950">Create Category</h1>
            <p class="mt-2 text-slate-600">Add a new game category</p>
        </div>

        <div class="card-surface p-8">
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="form-group">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="4">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="btn-primary flex-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Category
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn-secondary flex-1">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
