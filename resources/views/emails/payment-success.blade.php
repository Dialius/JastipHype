<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: #fff;
            padding: 40px 30px;
            text-align: center;
        }
        .checkmark {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
        }
        .content {
            padding: 40px 30px;
        }
        .amount-box {
            background: #f8f9fa;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .amount {
            font-size: 32px;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
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
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 30px 0;
        }
        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
        }
        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            font-weight: 600;
            color: #000;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="checkmark">✓</div>
            <h1 style="margin: 0; font-size: 28px;">Payment Successful!</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Your payment has been processed</p>
        </div>
        
        <div class="content">
            <p style="font-size: 16px;">Hi <strong>{{ $order->customer_name }}</strong>,</p>
            
            <p>Great news! We've successfully received your payment. Your order is now being processed and will be shipped soon.</p>
            
            <div class="amount-box">
                <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Amount Paid</div>
                <div class="amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                <div style="font-size: 14px; color: #666; margin-top: 5px;">Order #{{ $order->order_number }}</div>
            </div>
            
            <h3 style="margin: 30px 0 15px 0;">Payment Details</h3>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Payment Method</div>
                    <div class="info-value">{{ $order->payment->payment_method ?? 'N/A' }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Transaction ID</div>
                    <div class="info-value">{{ $order->payment->transaction_id ?? 'N/A' }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Payment Date</div>
                    <div class="info-value">{{ $order->payment->paid_at ? $order->payment->paid_at->format('M d, Y H:i') : now()->format('M d, Y H:i') }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value" style="color: #28a745;">✓ Paid</div>
                </div>
            </div>
            
            <div style="background: #e7f5ff; border-left: 4px solid #0066cc; padding: 15px; margin: 30px 0; border-radius: 4px;">
                <strong>📦 What Happens Next?</strong><br>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    <li>We'll prepare your order for shipment</li>
                    <li>You'll receive a shipping confirmation email with tracking number</li>
                    <li>Your order will be delivered within the estimated time</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ config('app.url') }}/profile?tab=orders" class="button">
                    View Order Details
                </a>
            </div>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 30px;">
                <h4 style="margin: 0 0 10px 0;">Need Help?</h4>
                <p style="margin: 0; font-size: 14px; color: #666;">
                    If you have any questions about your payment or order, please contact our customer support team. We're here to help!
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p style="margin: 0;">© {{ date('Y') }} JastipHype. All rights reserved.</p>
            <p style="margin: 5px 0 0 0;">Limited Edition Fashion & Exclusive Drops</p>
        </div>
    </div>
</body>
</html>
