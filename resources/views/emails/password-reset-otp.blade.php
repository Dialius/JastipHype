<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: #000;
            color: #fff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .otp-box {
            background: #f8f9fa;
            border: 2px dashed #d4af37;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #000;
            margin: 10px 0;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #000;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Password Reset Request</h1>
        </div>
        
        <div class="content">
            <p>Hi <strong>{{ $userName }}</strong>,</p>
            
            <p>We received a request to reset your password for your JastipHype account. Use the OTP code below to reset your password:</p>
            
            <div class="otp-box">
                <p style="margin: 0; font-size: 14px; color: #666;">Your OTP Code</p>
                <div class="otp-code">{{ $otp }}</div>
                <p style="margin: 0; font-size: 12px; color: #999;">Valid for 60 minutes</p>
            </div>
            
            <p>Enter this code on the password reset page to continue.</p>
            
            <div class="warning">
                <strong>⚠️ Security Notice:</strong><br>
                • Do not share this code with anyone<br>
                • JastipHype will never ask for your OTP via phone or email<br>
                • If you didn't request this, please ignore this email
            </div>
            
            <p style="margin-top: 30px; color: #666; font-size: 14px;">
                If you didn't request a password reset, you can safely ignore this email. Your password will remain unchanged.
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 0;">© {{ date('Y') }} JastipHype. All rights reserved.</p>
            <p style="margin: 5px 0 0 0;">Limited Edition Fashion & Exclusive Drops</p>
        </div>
    </div>
</body>
</html>
