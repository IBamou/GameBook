@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-950">Create Game</h1>
            <p class="mt-2 text-slate-600">Add a new board game to your collection</p>
        </div>

        <div class="card-surface p-8">
            <form action="{{ route('games.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group col-span-2 sm:col-span-1">
                        <label class="form-label">Game Name *</label>
                        <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group col-span-2 sm:col-span-1">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-input">
                            <option value="">Select category</option>
                            @foreach(\App\Models\Category::all() as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="4">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="form-group">
                        <label class="form-label">Difficulty *</label>
                        <select name="difficulty" class="form-input" required>
                            <option value="">Select level</option>
                            <option value="easy" @selected(old('difficulty') == 'easy')>Easy</option>
                            <option value="medium" @selected(old('difficulty') == 'medium')>Medium</option>
                            <option value="hard" @selected(old('difficulty') == 'hard')>Hard</option>
                        </select>
                        @error('difficulty') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Min Players *</label>
                        <input type="number" name="min_players" class="form-input" value="{{ old('min_players') }}" min="1" required>
                        @error('min_players') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Max Players *</label>
                        <input type="number" name="max_players" class="form-input" value="{{ old('max_players') }}" min="1" required>
                        @error('max_players') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="form-group">
                        <label class="form-label">Available Spots *</label>
                        <input type="number" name="spots" class="form-input" value="{{ old('spots') }}" min="1" required>
                        @error('spots') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Duration (min) *</label>
                        <input type="number" name="duration" class="form-input" value="{{ old('duration') }}" min="1" required>
                        @error('duration') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price (MAD) *</label>
                        <input type="number" name="price" class="form-input" value="{{ old('price') }}" step="0.01" min="0" required>
                        @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Image URL</label>
                    <input type="url" name="image_url" class="form-input" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg">
                    @error('image_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="btn-primary flex-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Game
                    </button>
                    <a href="{{ route('games.index') }}" class="btn-secondary flex-1">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
