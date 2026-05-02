@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-950">Create Table</h1>
            <p class="mt-2 text-slate-600">Add a new game table to your venue</p>
        </div>

        <div class="card-surface p-8">
            <form action="{{ route('tables.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="form-group">
                    <label class="form-label">Table Reference *</label>
                    <input type="text" name="reference" class="form-input" value="{{ old('reference') }}" placeholder="e.g., Table A1" required>
                    @error('reference') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Seating Capacity *</label>
                    <input type="number" name="capacity" class="form-input" value="{{ old('capacity') }}" min="2" required>
                    @error('capacity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="btn-primary flex-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Table
                    </button>
                    <a href="{{ route('tables.index') }}" class="btn-secondary flex-1">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
