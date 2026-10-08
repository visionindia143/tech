<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 640px; margin: 0 auto; padding: 20px; }
        .header { background: #FF6B35; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .body { background: #f9f9f9; padding: 24px; border-radius: 0 0 8px 8px; }
        .field { margin-bottom: 12px; }
        .field strong { color: #2D3142; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Diagnostic Test Booking</h2>
        </div>
        <div class="body">
            <div class="field"><strong>Customer:</strong> {{ $booking->name }}</div>
            <div class="field"><strong>Email:</strong> {{ $booking->email }}</div>
            <div class="field"><strong>Phone:</strong> {{ $booking->phone }}</div>
            <div class="field"><strong>Pet Type:</strong> {{ $booking->pet_type ?: '-' }}</div>
            <div class="field"><strong>Pet Name:</strong> {{ $booking->pet_name ?: '-' }}</div>
            <div class="field"><strong>Test:</strong> {{ $booking->testType->name ?? '-' }}</div>
            <div class="field"><strong>Category:</strong> {{ $booking->testType->category ?? '-' }}</div>
            <div class="field"><strong>City:</strong> {{ $booking->city->name ?? '-' }}</div>
            <div class="field"><strong>Preferred Date:</strong> {{ $booking->preferred_date->format('F d, Y') }}</div>
            @if($booking->preferred_time)
                <div class="field"><strong>Preferred Time:</strong> {{ $booking->preferred_time }}</div>
            @endif
            @if($booking->address)
                <div class="field"><strong>Address:</strong> {{ $booking->address }}</div>
            @endif
            @if($booking->symptoms)
                <div class="field"><strong>Symptoms:</strong> {{ $booking->symptoms }}</div>
            @endif
            @if($booking->medical_history)
                <div class="field"><strong>Medical History:</strong> {{ $booking->medical_history }}</div>
            @endif
            @if($booking->message)
                <div class="field"><strong>Message:</strong> {{ $booking->message }}</div>
            @endif
            <hr style="border:none;border-top:1px solid #ddd;margin:20px 0;">
            <p style="font-size:13px;color:#888;">This booking was submitted on {{ $booking->created_at->format('M d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>