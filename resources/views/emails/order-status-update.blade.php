<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status Pesanan</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 30px; }
        .status-box { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #10b981; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Update Status Pesanan</h1>
        </div>
        
        <div class="content">
            <p>Halo {{ $order->user->name }},</p>
            
            <p>Status pesanan Anda telah diperbarui.</p>
            
            <div class="status-box">
                <h2>Pesanan #{{ $order->order_number }}</h2>
                <p><strong>Status Sebelumnya:</strong> {{ ucfirst($oldStatus) }}</p>
                <p><strong>Status Baru:</strong> <span style="color: #10b981; font-weight: bold;">{{ ucfirst($newStatus) }}</span></p>
                <p><strong>Tanggal Update:</strong> {{ now()->format('d F Y, H:i') }}</p>
            </div>
            
            @if($newStatus === 'shipped')
            <p>Pesanan Anda sedang dalam perjalanan! Anda akan menerima notifikasi ketika pesanan tiba.</p>
            @elseif($newStatus === 'delivered')
            <p>Pesanan Anda telah sampai! Terima kasih telah berbelanja di JastipHype.</p>
            @elseif($newStatus === 'cancelled')
            <p>Pesanan Anda telah dibatalkan. Jika Anda memiliki pertanyaan, silakan hubungi customer support kami.</p>
            @endif
            
            <p style="margin-top: 30px;">
                <a href="{{ config('app.url') }}/orders/{{ $order->id }}" 
                   style="background: #4F46E5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">
                    Lihat Detail Pesanan
                </a>
            </p>
        </div>
        
        <div class="footer">
            <p>Jika Anda memiliki pertanyaan, silakan hubungi kami di {{ config('mail-addresses.support') }}</p>
            <p>&copy; {{ date('Y') }} JastipHype. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
