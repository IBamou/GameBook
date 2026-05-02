@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header mb-8">
            <div class="section-title">
                <h1>Reservation Details</h1>
                <p class="text-slate-500">Booking #{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('reservations.edit', $reservation) }}" class="btn-secondary text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('reservations.delete', $reservation) }}" method="POST" onsubmit="return confirm('Cancel this reservation?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary text-sm text-red-600 hover:bg-red-50">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Cancel
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card-surface p-6">
                <h2 class="text-lg font-semibold text-slate-950 mb-4">Details</h2>
                <div class="space-y-4">
                    <div class="flex justify-between py-3 border-b border-slate-200">
                        <span class="text-slate-600">User</span>
                        <span class="font-semibold text-slate-900">{{ $reservation->user->name }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-slate-200">
                        <span class="text-slate-600">Table</span>
                        <span class="font-semibold text-slate-900">Table {{ $reservation->table->reference }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-slate-200">
                        <span class="text-slate-600">Game</span>
                        <span class="font-semibold text-slate-900">{{ $reservation->game->name ?? 'None' }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-slate-200">
                        <span class="text-slate-600">Date</span>
                        <span class="font-semibold text-slate-900">{{ \Carbon\Carbon::parse($reservation->date)->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-slate-200">
                        <span class="text-slate-600">Time</span>
                        <span class="font-semibold text-slate-900">
                            {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                        </span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-slate-200">
                        <span class="text-slate-600">Spots</span>
                        <span class="font-semibold text-slate-900">{{ $reservation->spots }}/{{ $reservation->table->capacity }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-slate-200">
                        <span class="text-slate-600">Price</span>
                        <span class="font-semibold text-sky-600 text-lg">{{ number_format($reservation->price, 2) }} MAD</span>
                    </div>
                    <div class="flex justify-between py-3 pt-2">
                        <span class="text-slate-600">Status</span>
                        <span class="
                            {{ $reservation->status === 'confirmed' ? 'badge-confirmed' : '' }}
                            {{ $reservation->status === 'pending' ? 'badge-pending' : '' }}
                            {{ $reservation->status === 'cancelled' ? 'badge-cancelled' : '' }}
                            {{ $reservation->status === 'completed' ? 'badge-completed' : '' }}
                        ">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </div>
                </div>
            </div>

            @if(auth()->user()->role === 'admin' && $reservation->status === 'pending')
            <div class="card-surface p-6">
                <h2 class="text-lg font-semibold text-slate-950 mb-4">Admin Actions</h2>
                <div class="space-y-3">
                    <form action="{{ route('reservations.status', $reservation) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="btn-primary w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Confirm Reservation
                        </button>
                    </form>
                    <form action="{{ route('reservations.status', $reservation) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn-sm-danger w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject Reservation
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="card-surface p-6 bg-slate-50 border-slate-300">
                <h2 class="text-lg font-semibold text-slate-950 mb-4">Status Summary</h2>
                <div class="flex items-center gap-4">
                    @switch($reservation->status)
                        @case('confirmed')
                            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-950">Confirmed</h3>
                                <p class="text-sm text-slate-600">Your reservation is confirmed</p>
                            </div>
                            @break
                        @case('pending')
                            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-950">Pending Review</h3>
                                <p class="text-sm text-slate-600">Waiting for admin confirmation</p>
                            </div>
                            @break
                        @case('cancelled')
                            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-950">Cancelled</h3>
                                <p class="text-sm text-slate-600">This reservation has been cancelled</p>
                            </div>
                            @break
                        @case('completed')
                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-950">Completed</h3>
                                <p class="text-sm text-slate-600">Session finished</p>
                            </div>
                            @break
                    @endswitch
                </div>
            </div>
            @endif
        </div>

        @if($reservation->status === 'completed' && $reservation->game)
            @php
                $existingReview = \App\Models\Review::where('user_id', auth()->id())
                    ->where('reservation_id', $reservation->id)
                    ->first();
            @endphp

            @if(!$existingReview)
            <div class="mt-8 card-surface p-6">
                <h2 class="text-lg font-semibold text-slate-950 mb-4">Rate this session</h2>
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                    <input type="hidden" name="game_id" value="{{ $reservation->game_id }}">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Rating</label>
                        <div class="flex gap-2" id="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="star-btn text-3xl" data-rating="{{ $i }}">
                                    <svg class="w-8 h-8 text-slate-300 hover:text-amber-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            @endfor
                            <input type="hidden" name="rating" id="rating-value" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Comment (optional)</label>
                        <textarea name="comment" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-500" placeholder="Share your experience..."></textarea>
                    </div>

                    <button type="submit" class="btn-primary">Submit Review</button>
                </form>
            </div>

            @script
            <script>
                const stars = document.querySelectorAll('.star-btn');
                const ratingInput = document.getElementById('rating-value');
                
                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const rating = this.dataset.rating;
                        ratingInput.value = rating;
                        
                        stars.forEach((s, index) => {
                            const svg = s.querySelector('svg');
                            if (index < rating) {
                                svg.classList.remove('text-slate-300');
                                svg.classList.add('text-amber-400');
                            } else {
                                svg.classList.remove('text-amber-400');
                                svg.classList.add('text-slate-300');
                            }
                        });
                    });
                });
            </script>
            @endscript
            @else
            <div class="mt-8 card-surface p-6 bg-slate-50">
                <h2 class="text-lg font-semibold text-slate-950 mb-2">Your Review</h2>
                <div class="flex mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= $existingReview->rating ? 'text-amber-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                @if($existingReview->comment)
                    <p class="text-slate-700">{{ $existingReview->comment }}</p>
                @endif
            </div>
            @endif
        @endif
    </div>
</div>
@endsection
