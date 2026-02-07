<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Baru dari Contact Form</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 30px; }
        .message-box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pesan Baru dari Contact Form</h1>
        </div>
        
        <div class="content">
            <div class="message-box">
                <p><strong>Dari:</strong> {{ $data['name'] ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $data['email'] ?? 'N/A' }}</p>
                <p><strong>Telepon:</strong> {{ $data['phone'] ?? 'N/A' }}</p>
                <p><strong>Subjek:</strong> {{ $data['subject'] ?? 'N/A' }}</p>
                
                <hr style="margin: 20px 0; border: none; border-top: 1px solid #e5e7eb;">
                
                <p><strong>Pesan:</strong></p>
                <p style="white-space: pre-wrap;">{{ $data['message'] ?? 'N/A' }}</p>
            </div>
            
            <p style="color: #6b7280; font-size: 14px;">
                Pesan ini dikirim dari contact form website JastipHype pada {{ now()->format('d F Y, H:i') }}
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} JastipHype. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
