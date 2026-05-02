@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header">
            <div class="section-title">
                <h1>Export Data</h1>
                <p>Download your data in CSV format</p>
            </div>
        </div>

        <div class="grid gap-6">
            <div class="card-surface p-6">
                <h2 class="text-lg font-semibold text-slate-950 mb-4">Reservations</h2>
                <form action="{{ route('export.reservations') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">From Date</label>
                        <input type="date" name="date_from" value="{{ now()->startOfMonth()->toDateString() }}" 
                            class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">To Date</label>
                        <input type="date" name="date_to" value="{{ now()->endOfMonth()->toDateString() }}" 
                            class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select name="status" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download CSV
                    </button>
                </form>
            </div>

            <div class="card-surface p-6">
                <h2 class="text-lg font-semibold text-slate-950 mb-4">Sessions</h2>
                <form action="{{ route('export.sessions') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">From Date</label>
                        <input type="date" name="date_from" value="{{ now()->startOfMonth()->toDateString() }}" 
                            class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">To Date</label>
                        <input type="date" name="date_to" value="{{ now()->endOfMonth()->toDateString() }}" 
                            class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select name="status" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                            <option value="">All</option>
                            <option value="inactive">Inactive</option>
                            <option value="active">Active</option>
                            <option value="ended">Ended</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download CSV
                    </button>
                </form>
            </div>

            <div class="card-surface p-6">
                <h2 class="text-lg font-semibold text-slate-950 mb-4">Revenue Report</h2>
                <form action="{{ route('export.revenue') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">From Date</label>
                        <input type="date" name="date_from" value="{{ now()->startOfMonth()->toDateString() }}" 
                            class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">To Date</label>
                        <input type="date" name="date_to" value="{{ now()->endOfMonth()->toDateString() }}" 
                            class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500">
                    </div>
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download CSV
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection