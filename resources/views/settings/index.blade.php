@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header">
            <div class="section-title">
                <h1>Settings</h1>
                <p>Configure your application preferences</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                <p class="text-emerald-700">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            
            <div class="card-surface p-6 mb-6">
                <h2 class="text-lg font-semibold text-slate-950 mb-4">Appearance</h2>
                
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-slate-900">Theme</p>
                        <p class="text-sm text-slate-500">Choose your preferred color theme (Coming soon)</p>
                    </div>
                    <span class="px-4 py-2 bg-slate-100 text-slate-500 rounded-lg">Light Only</span>
                </div>
            </div>

            <div class="card-surface p-6 mb-6">
                <h2 class="text-lg font-semibold text-slate-950 mb-4">Session Settings</h2>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-slate-900">Auto-start session</p>
                        <p class="text-sm text-slate-500">Coming soon...</p>
                    </div>
                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-sm">Coming Soon</span>
                </div>
            </div>

            <div class="card-surface p-6 mb-6">
                <h2 class="text-lg font-semibold text-slate-950 mb-4">Reservation Settings</h2>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-slate-900">Auto-confirm reservations</p>
                        <p class="text-sm text-slate-500">Coming soon...</p>
                    </div>
                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-sm">Coming Soon</span>
                </div>
            </div>

            <div class="card-surface p-6 mb-6">
                <h2 class="text-lg font-semibold text-slate-950 mb-4">Email Notifications</h2>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-slate-900">Reminder emails</p>
                        <p class="text-sm text-slate-500">Coming soon...</p>
                    </div>
                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-sm">Coming Soon</span>
                </div>
            </div>

            <button type="button" class="btn-primary w-full opacity-50 cursor-not-allowed" disabled>Coming Soon</button>
        </form>
    </div>
</div>
@endsection