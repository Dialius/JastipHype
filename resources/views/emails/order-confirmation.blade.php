<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 30px; }
        .order-details { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .item { border-bottom: 1px solid #e5e7eb; padding: 10px 0; }
        .total { font-size: 18px; font-weight: bold; margin-top: 20px; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Thank You for Your Order!</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $order->user->name }},</p>
            
            <p>Your order has been received and is being processed.</p>
            
            <div class="order-details">
                <h2>Order Details</h2>
                <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('d F Y, H:i') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                
                <h3 style="margin-top: 20px;">Ordered Products:</h3>
                @foreach($order->items as $item)
                <div class="item">
                    <p><strong>{{ $item->product_name }}</strong></p>
                    <p>Quantity: {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    <p>Subtotal: Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
                </div>
                @endforeach
                
                <div class="total">
                    <p>Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <p><strong>Shipping Address:</strong></p>
            <p>{{ $order->shipping_address }}</p>
            
            <p>You can track your order status on your account page.</p>
            
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
