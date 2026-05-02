@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header mb-6">
            <div class="flex items-center justify-between">
                <div class="section-title">
                    <h1>Reservations Calendar</h1>
                    <p>{{ \Carbon\Carbon::createFromDate($year, $month)->format('F Y') }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('reservations.calendar', ['month' => $month == 1 ? 12 : $month - 1, 'year' => $month == 1 ? $year - 1 : $year]) }}" 
                       class="btn-secondary">
                        ← Previous
                    </a>
                    <a href="{{ route('reservations.calendar', ['month' => now()->month, 'year' => now()->year]) }}" 
                       class="btn-secondary">
                        Today
                    </a>
                    <a href="{{ route('reservations.calendar', ['month' => $month == 12 ? 1 : $month + 1, 'year' => $month == 12 ? $year + 1 : $year]) }}" 
                       class="btn-secondary">
                        Next →
                    </a>
                </div>
            </div>
        </div>

        @php
            $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            $startDay = $startOfMonth->dayOfWeek;
            $daysInMonth = $startOfMonth->daysInMonth;
            $today = now()->toDateString();
        @endphp

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="grid grid-cols-7 bg-slate-50 border-b">
                @foreach($daysOfWeek as $day)
                    <div class="px-2 py-3 text-center text-sm font-semibold text-slate-600">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-7">
                @for($i = 0; $i < $startDay; $i++)
                    <div class="min-h-[120px] bg-slate-50 border"></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentDate = \Carbon\Carbon::createFromDate($year, $month, $day)->toDateString();
                        $dayReservations = $reservations->filter(function($r) use ($currentDate) {
                            return $r->date === $currentDate;
                        });
                        $isToday = $currentDate === $today;
                    @endphp
                    <div class="min-h-[120px] border p-2 {{ $isToday ? 'bg-amber-50' : '' }}">
                        <div class="flex justify-between items-start mb-1">
                            <span class="text-sm font-medium {{ $isToday ? 'text-amber-600' : 'text-slate-700' }}">
                                {{ $day }}
                            </span>
                            @if($dayReservations->count() > 0)
                                <span class="text-xs bg-slate-200 text-slate-600 px-1.5 py-0.5 rounded">
                                    {{ $dayReservations->count() }}
                                </span>
                            @endif
                        </div>
                        
                        <div class="space-y-1">
                            @foreach($dayReservations->take(3) as $res)
                                <div class="text-xs p-1 rounded truncate
                                    {{ $res->status === 'confirmed' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $res->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $res->status === 'completed' ? 'bg-slate-100 text-slate-600' : '' }}">
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }}</span>
                                    <span>T{{ $res->table->reference }}</span>
                                </div>
                            @endforeach
                            
                            @if($dayReservations->count() > 3)
                                <div class="text-xs text-slate-500">+{{ $dayReservations->count() - 3 }} more</div>
                            @endif
                        </div>
                    </div>
                @endfor

                @php
                    $remainingCells = (7 - (($startDay + $daysInMonth) % 7)) % 7;
                    if ($remainingCells === 0 && ($startDay + $daysInMonth) % 7 !== 0) {
                        $remainingCells = 7 - (($startDay + $daysInMonth) % 7);
                    }
                    $totalCells = $startDay + $daysInMonth;
                    $remainingCells = ceil($totalCells / 7) * 7 - $totalCells;
                @endfor
                
                @for($i = 0; $i < $remainingCells; $i++)
                    <div class="min-h-[120px] bg-slate-50 border"></div>
                @endfor
            </div>
        </div>

        <div class="mt-6 flex gap-4 text-sm">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-amber-100 border rounded"></span>
                <span>Pending</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-emerald-100 border rounded"></span>
                <span>Confirmed</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-slate-100 border rounded"></span>
                <span>Completed</span>
            </div>
        </div>
    </div>
</div>
@endsection