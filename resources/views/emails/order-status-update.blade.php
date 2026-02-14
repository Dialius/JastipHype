<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
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
            <h1>Order Status Update</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $order->user->name }},</p>
            
            <p>Your order status has been updated.</p>
            
            <div class="status-box">
                <h2>Order #{{ $order->order_number }}</h2>
                <p><strong>Previous Status:</strong> {{ ucfirst($oldStatus) }}</p>
                <p><strong>New Status:</strong> <span style="color: #10b981; font-weight: bold;">{{ ucfirst($newStatus) }}</span></p>
                <p><strong>Update Date:</strong> {{ now()->format('d F Y, H:i') }}</p>
            </div>
            
            @if($newStatus === 'shipped')
            <p>Your order is on the way! You will receive a notification when the order arrives.</p>
            @elseif($newStatus === 'delivered')
            <p>Your order has been delivered! Thank you for shopping at JastipHype.</p>
            @elseif($newStatus === 'cancelled')
            <p>Your order has been cancelled. If you have any questions, please contact our customer support.</p>
            @endif
            
            <p style="margin-top: 30px;">
                <a href="{{ config('app.url') }}/orders/{{ $order->id }}" 
                   style="background: #4F46E5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">
                    View Order Details
                </a>
            </p>
        </div>
        
        <div class="footer">
            <p>If you have any questions, please contact us at {{ config('mail-addresses.support') }}</p>
            <p>&copy; {{ date('Y') }} JastipHype. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
