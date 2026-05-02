<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationSession;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function reservations(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth()->toDateString());
        $status = $request->get('status');

        $reservations = Reservation::with(['user', 'table', 'game'])
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $filename = 'reservations_' . Carbon::now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($reservations) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['ID', 'User Name', 'User Email', 'Table', 'Game', 'Date', 'Start Time', 'End Time', 'Spots', 'Status', 'Price', 'Created At']);

            foreach ($reservations as $r) {
                fputcsv($file, [
                    $r->id,
                    $r->user->name,
                    $r->user->email,
                    'Table ' . $r->table->reference,
                    $r->game->name ?? 'N/A',
                    $r->date,
                    $r->start_time,
                    $r->end_time,
                    $r->spots,
                    $r->status,
                    number_format($r->price, 2),
                    $r->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function sessions(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth()->toDateString());
        $status = $request->get('status');

        $sessions = ReservationSession::whereHas('reservation', function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('date', [$dateFrom, $dateTo]);
            })
            ->with(['reservation.user', 'reservation.table', 'game'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('started_at')
            ->get();

        $filename = 'sessions_' . Carbon::now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($sessions) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['ID', 'User', 'Table', 'Game', 'Started At', 'Ended At', 'Duration (min)', 'Status', 'Additional Charges', 'Total']);

            foreach ($sessions as $s) {
                $total = ($s->reservation->price ?? 0) + ($s->additional_charges ?? 0);
                
                fputcsv($file, [
                    $s->id,
                    $s->reservation->user->name ?? 'N/A',
                    'Table ' . ($s->reservation->table->reference ?? 'N/A'),
                    $s->game->name ?? 'N/A',
                    $s->started_at ?? 'N/A',
                    $s->ended_at ?? 'N/A',
                    $s->duration,
                    $s->status,
                    number_format($s->additional_charges ?? 0, 2),
                    number_format($total, 2),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function revenue(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth()->toDateString());

        $reservations = Reservation::whereBetween('date', [$dateFrom, $dateTo])
            ->where('status', '!=', 'cancelled')
            ->get();

        $sessions = ReservationSession::whereHas('reservation', function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('date', [$dateFrom, $dateTo]);
            })
            ->where('status', 'ended')
            ->get();

        $totalReservations = $reservations->sum('price');
        $totalAdditional = $sessions->sum('additional_charges');
        $totalRevenue = $totalReservations + $totalAdditional;

        $filename = 'revenue_' . Carbon::now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($dateFrom, $dateTo, $reservations, $sessions, $totalReservations, $totalAdditional, $totalRevenue) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['GameBook Revenue Report']);
            fputcsv($file, ['Period', "$dateFrom to $dateTo"]);
            fputcsv($file, []);
            fputcsv($file, ['Summary']);
            fputcsv($file, ['Total Reservation Revenue', number_format($totalReservations, 2) . ' MAD']);
            fputcsv($file, ['Total Additional Charges', number_format($totalAdditional, 2) . ' MAD']);
            fputcsv($file, ['Total Revenue', number_format($totalRevenue, 2) . ' MAD']);
            fputcsv($file, []);
            fputcsv($file, ['By Status']);
            
            $statusGroups = $reservations->groupBy('status');
            foreach ($statusGroups as $status => $items) {
                fputcsv($file, [ucfirst($status), number_format($items->sum('price'), 2) . ' MAD (' . $items->count() . ' reservations)']);
            }
            
            fputcsv($file, []);
            fputcsv($file, ['Daily Breakdown']);
            fputcsv($file, ['Date', 'Reservations', 'Revenue']);
            
            $dailyGroups = $reservations->groupBy('date');
            foreach ($dailyGroups->sortKeys() as $date => $items) {
                fputcsv($file, [$date, $items->count(), number_format($items->sum('price'), 2) . ' MAD']);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}