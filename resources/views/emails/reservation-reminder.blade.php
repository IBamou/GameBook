<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reservation Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f59e0b; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f8fafc; padding: 20px; border: 1px solid #e2e8f0; }
        .details { background: white; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
        .footer { text-align: center; padding: 20px; color: #64748b; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin:0;">GameBook</h1>
        <p style="margin:5px 0 0;">⏰ Reservation Reminder</p>
    </div>
    <div class="content">
        <p>Hello <strong>{{ $reservation->user->name }}</strong>,</p>
        <p>Don't forget! Your reservation is coming up soon.</p>
        
        <div class="details">
            <div class="detail-row"><span>Date</span><span>{{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }}</span></div>
            <div class="detail-row"><span>Time</span><span>{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}</span></div>
            <div class="detail-row"><span>Table</span><span>Table {{ $reservation->table->reference }}</span></div>
            @if($reservation->game)<div class="detail-row"><span>Game</span><span>{{ $reservation->game->name }}</span></div>@endif
        </div>
        
        <p>See you soon!</p>
    </div>
    <div class="footer"><p>Thank you for choosing GameBook!</p></div>
</body>
</html>