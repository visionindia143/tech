<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #FF6B35; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .body { background: #f9f9f9; padding: 24px; border-radius: 0 0 8px 8px; }
        .field { margin-bottom: 12px; }
        .field strong { color: #2D3142; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🐾 New Vaccination Booking</h2>
        </div>
        <div class="body">
            <div class="field"><strong>Customer:</strong> {{ $booking->name }}</div>
            <div class="field"><strong>Email:</strong> {{ $booking->email }}</div>
            <div class="field"><strong>Phone:</strong> {{ $booking->phone }}</div>
            <div class="field"><strong>Pet:</strong> {{ $booking->pet_name }} ({{ $booking->pet_type }})</div>
            <div class="field"><strong>Vaccination:</strong> {{ $booking->vaccinationType->name ?? '-' }}</div>
            <div class="field"><strong>City:</strong> {{ $booking->city->name ?? '-' }}</div>
            <div class="field"><strong>Preferred Date:</strong> {{ $booking->preferred_date->format('F d, Y') }}</div>
            @if($booking->message)
                <div class="field"><strong>Message:</strong> {{ $booking->message }}</div>
            @endif
            <hr style="border:none;border-top:1px solid #ddd;margin:20px 0;">
            <p style="font-size:13px;color:#888;">This booking was submitted on {{ $booking->created_at->format('M d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>
