<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Spot Available</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #10b981; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f8fafc; padding: 20px; border: 1px solid #e2e8f0; }
        .btn { display: inline-block; padding: 12px 24px; background: #0f172a; color: white; text-decoration: none; border-radius: 6px; margin: 15px 0; }
        .footer { text-align: center; padding: 20px; color: #64748b; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin:0;">GameBook</h1>
        <p style="margin:5px 0 0;">🎉 A Spot Opened!</p>
    </div>
    <div class="content">
        <p>Hello <strong>{{ $waitlist->user->name }}</strong>,</p>
        <p>Great news! A spot has opened up on your waitlist.</p>
        
        <p><strong>Table:</strong> Table {{ $waitlist->table->reference }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($waitlist->date)->format('l, F j, Y') }}</p>
        <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($waitlist->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($waitlist->end_time)->format('H:i') }}</p>
        
        <a href="{{ route('reservations.create') }}?tab={{ $waitlist->table_id }}&date={{ $waitlist->date }}&time={{ $waitlist->start_time }}" class="btn">
            Reserve Now
        </a>
        
        <p><small>This offer expires in 24 hours.</small></p>
    </div>
    <div class="footer"><p>Thank you for choosing GameBook!</p></div>
</body>
</html>