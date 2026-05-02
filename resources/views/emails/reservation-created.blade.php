<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservation Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #0f172a; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f8fafc; padding: 20px; border: 1px solid #e2e8f0; }
        .details { background: white; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
        .detail-row:last-child { border-bottom: none; }
        .label { font-weight: 600; color: #64748b; }
        .value { color: #0f172a; }
        .status { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 14px; font-weight: 600; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #dcfce7; color: #166534; }
        .footer { text-align: center; padding: 20px; color: #64748b; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin:0;">GameBook</h1>
        <p style="margin:5px 0 0;">Reservation Confirmation</p>
    </div>
    <div class="content">
        <p>Hello <strong>{{ $reservation->user->name }}</strong>,</p>
        <p>Your reservation has been created successfully. Here are the details:</p>
        
        <div class="details">
            <div class="detail-row">
                <span class="label">Reference</span>
                <span class="value">#{{ $reservation->id }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Date</span>
                <span class="value">{{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Time</span>
                <span class="value">{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Table</span>
                <span class="value">Table {{ $reservation->table->reference }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Players</span>
                <span class="value">{{ $reservation->spots }}</span>
            </div>
            @if($reservation->game)
            <div class="detail-row">
                <span class="label">Game</span>
                <span class="value">{{ $reservation->game->name }}</span>
            </div>
            @endif
            <div class="detail-row">
                <span class="label">Status</span>
                <span class="status status-pending">{{ ucfirst($reservation->status) }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Total Price</span>
                <span class="value">{{ number_format($reservation->price, 2) }} MAD</span>
            </div>
        </div>
        
        <p>Your reservation is currently <strong>pending</strong> and awaits confirmation from our team.</p>
    </div>
    <div class="footer">
        <p>Thank you for choosing GameBook!</p>
        <p>&copy; {{ date('Y') }} GameBook. All rights reserved.</p>
    </div>
</body>
</html>