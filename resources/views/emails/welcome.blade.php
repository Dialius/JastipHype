<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to JastipHype</title>
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
            background: linear-gradient(135deg, #000 0%, #1a1a1a 100%);
            color: #fff;
            padding: 40px 30px;
            text-align: center;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .gold {
            color: #d4af37;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #d4af37;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .features {
            margin: 30px 0;
        }
        .feature-item {
            display: flex;
            align-items: start;
            margin-bottom: 15px;
        }
        .feature-icon {
            width: 24px;
            height: 24px;
            background: #d4af37;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background: #000;
            color: #fff !important;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: 600;
        }
        .button:hover {
            background: #1a1a1a;
        }
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #666;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                JASTIP<span class="gold">HYPE</span>
            </div>
            <p style="margin: 0; font-size: 18px; opacity: 0.9;">Premium Streetwear Destination</p>
        </div>
        
        <div class="content">
            <h1 style="margin: 0 0 20px 0; font-size: 28px;">Welcome, {{ $userName }}! 🎉</h1>
            
            <p style="font-size: 16px; color: #555;">
                Thank you for joining <strong>JastipHype</strong>! We're excited to have you as part of our exclusive community of streetwear enthusiasts.
            </p>
            
            <div class="welcome-box">
                <h3 style="margin: 0 0 10px 0; color: #000;">Your Account is Ready!</h3>
                <p style="margin: 0; color: #666;">
                    You now have access to limited edition drops, exclusive collections, and premium streetwear from the world's top brands.
                </p>
            </div>
            
            <h3 style="margin: 30px 0 15px 0;">What You Can Do Now:</h3>
            
            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">✓</div>
                    <div>
                        <strong>Browse Exclusive Collections</strong><br>
                        <span style="color: #666; font-size: 14px;">Discover limited edition items from Supreme, Off-White, BAPE, and more</span>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">✓</div>
                    <div>
                        <strong>Create Your Wishlist</strong><br>
                        <span style="color: #666; font-size: 14px;">Save your favorite items and get notified when they're back in stock</span>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">✓</div>
                    <div>
                        <strong>Fast & Secure Checkout</strong><br>
                        <span style="color: #666; font-size: 14px;">Multiple payment options with secure transaction processing</span>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">✓</div>
                    <div>
                        <strong>Track Your Orders</strong><br>
                        <span style="color: #666; font-size: 14px;">Real-time updates on your order status and delivery</span>
                    </div>
                </div>
            </div>
            
            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ config('app.url') }}/products" class="button">
                    Start Shopping Now
                </a>
            </div>
            
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 30px 0; border-radius: 4px;">
                <strong>💡 Pro Tip:</strong> Follow us on social media to get early access to new drops and exclusive member-only deals!
            </div>
        </div>
        
        <div class="footer">
            <p style="margin: 0 0 15px 0; font-weight: 600; color: #000;">JASTIP<span style="color: #d4af37;">HYPE</span></p>
            <p style="margin: 0 0 10px 0;">Limited Edition Fashion & Exclusive Drops</p>
            
            <div class="social-links">
                <a href="#">Instagram</a> • 
                <a href="#">Twitter</a> • 
                <a href="#">Facebook</a>
            </div>
            
            <p style="margin: 20px 0 0 0; font-size: 12px; color: #999;">
                © {{ date('Y') }} JastipHype. All rights reserved.<br>
                You're receiving this email because you created an account with us.
            </p>
        </div>
    </div>
</body>
</html>
