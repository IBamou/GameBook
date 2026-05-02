<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reservation Cancelled</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc2626; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f8fafc; padding: 20px; border: 1px solid #e2e8f0; }
        .footer { text-align: center; padding: 20px; color: #64748b; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin:0;">GameBook</h1>
        <p style="margin:5px 0 0;">Reservation Cancelled</p>
    </div>
    <div class="content">
        <p>Hello <strong>{{ $reservation->user->name }}</strong>,</p>
        <p>Your reservation has been cancelled.</p>
        <p><strong>Reference:</strong> #{{ $reservation->id }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($reservation->date)->format('F j, Y') }}</p>
        <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}</p>
        
        <p>If you did not request this cancellation, please contact us.</p>
    </div>
    <div class="footer"><p>Thank you for choosing GameBook!</p></div>
</body>
</html>