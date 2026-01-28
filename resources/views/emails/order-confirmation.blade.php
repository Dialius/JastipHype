<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
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
        .success-badge {
            background: #d4af37;
            color: #000;
            padding: 8px 20px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .order-box {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-number {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            margin-bottom: 10px;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .item-row:last-child {
            border-bottom: none;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            font-weight: bold;
            font-size: 18px;
            border-top: 2px solid #000;
            margin-top: 10px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #000;
            color: #fff !important;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: 600;
        }
        .info-box {
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .detail-table td {
            padding: 8px 0;
            vertical-align: top;
        }
        .detail-table td:first-child {
            color: #666;
            width: 40%;
        }
        .detail-table td:last-child {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Order Confirmed!</h1>
        </div>
        
        <div class="content">
            <div style="text-align: center;">
                <span class="success-badge">ORDER PLACED</span>
            </div>
            
            <p style="font-size: 16px;">Hi <strong>{{ $order->customer_name }}</strong>,</p>
            
            <p>Thank you for your order! We've received your payment and are preparing your items for shipment.</p>
            
            <div class="order-box">
                <div class="order-number">Order #{{ $order->order_number }}</div>
                <p style="margin: 0; color: #666; font-size: 14px;">
                    Placed on {{ $order->created_at->format('F d, Y \a\t H:i') }}
                </p>
            </div>
            
            <h3 style="margin: 30px 0 15px 0;">Order Details</h3>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                @foreach($order->items as $item)
                <div class="item-row">
                    <div>
                        <strong>{{ $item->product_name }}</strong><br>
                        <span style="color: #666; font-size: 14px;">
                            @if($item->size) Size: {{ $item->size }} • @endif
                            Qty: {{ $item->quantity }}
                        </span>
                    </div>
                    <div style="text-align: right;">
                        <strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong>
                    </div>
                </div>
                @endforeach
                
                <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #dee2e6;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span>Shipping ({{ $order->shipping_courier }})</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; color: #28a745;">
                        <span>Discount</span>
                        <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
                
                <div class="total-row">
                    <span>Total</span>
                    <span style="color: #d4af37;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <h3 style="margin: 30px 0 15px 0;">Shipping Information</h3>
            
            <table class="detail-table">
                <tr>
                    <td>Recipient</td>
                    <td>{{ $order->customer_name }}</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>{{ $order->customer_phone }}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>{{ $order->shipping_address }}</td>
                </tr>
                <tr>
                    <td>Courier</td>
                    <td>{{ $order->shipping_courier }} - {{ $order->shipping_service }}</td>
                </tr>
                <tr>
                    <td>Estimated Delivery</td>
                    <td>{{ $order->estimated_delivery ?? '2-3 business days' }}</td>
                </tr>
            </table>
            
            <h3 style="margin: 30px 0 15px 0;">Payment Information</h3>
            
            <table class="detail-table">
                <tr>
                    <td>Payment Method</td>
                    <td>{{ $order->payment->payment_method ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Payment Status</td>
                    <td><span style="color: #28a745;">✓ Paid</span></td>
                </tr>
                <tr>
                    <td>Transaction ID</td>
                    <td>{{ $order->payment->transaction_id ?? 'N/A' }}</td>
                </tr>
            </table>
            
            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ config('app.url') }}/profile?tab=orders" class="button">
                    Track Your Order
                </a>
            </div>
            
            <div class="info-box">
                <strong>📦 What's Next?</strong><br>
                We'll send you another email with tracking information once your order ships. You can also track your order status anytime from your account.
            </div>
            
            <p style="margin-top: 30px; color: #666; font-size: 14px;">
                If you have any questions about your order, please don't hesitate to contact our customer support.
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 0;">© {{ date('Y') }} JastipHype. All rights reserved.</p>
            <p style="margin: 5px 0 0 0;">Limited Edition Fashion & Exclusive Drops</p>
        </div>
    </div>
</body>
</html>
