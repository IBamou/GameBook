<?php

namespace App\Console\Commands;

use App\Mail\ReservationReminder;
use App\Models\Reservation;
use App\Models\Setting;
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
        $enabled = Setting::where('key', 'send_reminder_email')->value('value') ?? '1';
        
        if ($enabled !== '1') {
            $this->info('Reminder emails are disabled.');
            return;
        }

        $hoursBefore = (int) (Setting::where('key', 'reminder_hours_before')->value('value') ?? 2);
        $targetTime = Carbon::now()->addHours($hoursBefore);
        
        $reservations = Reservation::where('status', 'confirmed')
            ->whereDate('date', Carbon::today())
            ->whereTime('start_time', '>=', Carbon::now()->format('H:i:s'))
            ->whereTime('start_time', '<=', $targetTime->format('H:i:s'))
            ->whereDoesntHave('sessions', function ($q) {
                $q->where('status', 'active');
            })
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