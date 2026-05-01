@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Create Table</h1>

        <form action="{{ route('tables.store') }}" method="POST" class="max-w-md">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Reference</label>
                <input type="text" name="reference" class="w-full border rounded-lg px-4 py-2" placeholder="T1" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Capacity</label>
                <input type="number" name="capacity" class="w-full border rounded-lg px-4 py-2" min="1" required>
            </div>
            <div class="flex gap-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
                <a href="{{ route('tables.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection