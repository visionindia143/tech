<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3B82F6; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .body { background: #f9f9f9; padding: 24px; border-radius: 0 0 8px 8px; }
        .field { margin-bottom: 12px; }
        .field strong { color: #2D3142; }
        .message-box { background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 14px; margin-top: 4px; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Contact Message</h2>
        </div>
        <div class="body">
            <div class="field"><strong>Name:</strong> {{ $contact->name }}</div>
            <div class="field"><strong>Email:</strong> {{ $contact->email }}</div>
            @if($contact->phone)
                <div class="field"><strong>Phone:</strong> {{ $contact->phone }}</div>
            @endif
            @if($contact->subject)
                <div class="field"><strong>Subject:</strong> {{ $contact->subject }}</div>
            @endif
            <div class="field">
                <strong>Message:</strong>
                <div class="message-box">{{ $contact->message }}</div>
            </div>
            <hr style="border:none;border-top:1px solid #ddd;margin:20px 0;">
            <p style="font-size:13px;color:#888;">Submitted on {{ $contact->created_at->format('M d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>
