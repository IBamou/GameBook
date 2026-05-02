<?php

namespace App\Console\Commands;

use App\Mail\ReservationReminder;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('app:send-reminders')]
#[Description('Send reminder emails for upcoming reservations')]
class SendReminders extends Command
{
    public function handle()
    {
        $tomorrow = Carbon::tomorrow();
        
        $reservations = Reservation::where('status', 'confirmed')
            ->whereDate('date', $tomorrow)
            ->with(['user', 'table', 'game'])
            ->get();

        $count = 0;
        foreach ($reservations as $reservation) {
            if ($reservation->user && $reservation->user->email) {
                Mail::to($reservation->user->email)->send(new ReservationReminder($reservation));
                $count++;
            }
        }

        $this->info("Sent {$count} reminder emails.");
    }
}