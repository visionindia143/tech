{{-- Admin Password Reset OTP Email --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Password Reset OTP</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #FF6B35, #E85520); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #ffffff; padding: 30px; border: 1px solid #e0e0e0; border-top: none; border-radius: 0 0 10px 10px; }
        .otp-box { background: #f8f9fa; border: 2px solid #FF6B35; border-radius: 8px; padding: 20px; text-align: center; margin: 20px 0; }
        .otp-code { font-size: 32px; font-weight: bold; color: #FF6B35; letter-spacing: 4px; font-family: monospace; }
        .warning { background: #fff3cd; border: 1px solid #ffeeba; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PrettyPet Admin</h1>
            <p>Password Reset Request</p>
        </div>

        <div class="content">
            <h2>Hello Admin,</h2>

            <p>You have requested to reset your password for the PrettyPet admin panel. Use the OTP code below to complete the password reset process:</p>

            <div class="otp-box">
                <p style="margin: 0 0 10px 0; font-size: 16px;">Your OTP Code:</p>
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <div class="warning">
                <strong>⚠️ Important:</strong>
                <ul style="margin: 10px 0 0 20px; padding: 0;">
                    <li>This OTP will expire in 10 minutes</li>
                    <li>This OTP can only be used once</li>
                    <li>Do not share this OTP with anyone</li>
                </ul>
            </div>

            <p>If you did not request this password reset, please ignore this email. Your account remains secure.</p>

            <p>For security reasons, this OTP will be invalidated after use or expiration.</p>

            <p>Best regards,<br>
            PrettyPet Team</p>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} PrettyPet. All rights reserved.</p>
        </div>
    </div>
</body>
</html>