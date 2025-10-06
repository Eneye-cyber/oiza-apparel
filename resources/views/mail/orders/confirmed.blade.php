<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Oiza Apparels</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 0;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #ffffff;
            padding: 32px 20px;
            text-align: center;
            border-bottom: 1px solid #e5e5e5;
        }
        .header h1 {
            margin: 0;
            font-family: 'Playfair Display', 'Georgia', 'Times New Roman', serif;
            font-size: 28px;
            color: #1a1a1a;
            font-weight: 400;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0 0 0;
            font-size: 12px;
            color: #656565;
            line-height: 1.4;
        }
        .navbar {
            /* background-color: #fafafa; */
            padding: 15px 16px;
            text-align: center;
            border-bottom: 1px solid #e5e5e5;
        }
        .navbar a {
            color: #656565;
            text-decoration: none;
            margin: 0 16px;
            font-size: 12px;
            font-weight: 500;
            transition: color 0.3s ease;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .navbar a:hover {
            color: #a67c00; /* Golden accent */
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            font-family: 'Playfair Display', 'Georgia', 'Times New Roman', serif;
            font-size: 20px;
            color: #1a1a1a;
            margin-bottom: 20px;
            margin-top: 0px;
            font-weight: 400;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .order-details {
            margin-bottom: 30px;
        }
        .order-details p {
            margin: 10px 0;
            font-size: 12px;
            color: #4d4d4d;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table th, table td {
            padding: 15px;
            border: 1px solid #e5e5e5;
            text-align: left;
            font-size: 12px;
            color: #333333;
        }
        table th {
            background-color: #fafafa;
            font-family: 'Playfair Display', 'Georgia', 'Times New Roman', serif;
            font-weight: 400;
            color: #1a1a1a;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        table img {
            max-width: 60px;
            max-height: 60px;
            height: auto;
            border-radius: 4px;
        }
        .totals {
            text-align: right;
            font-size: 12px;
            color: #4d4d4d;
        }
        .totals p {
            margin: 10px 0;
            line-height: 1.6;
        }
        .totals strong {
            color: #1a1a1a;
        }
        .address {
            margin-bottom: 30px;
        }
        .address h3 {
            font-family: 'Playfair Display', 'Georgia', 'Times New Roman', serif;
            font-size: 20px;
            margin-bottom: 15px;
            color: #1a1a1a;
            font-weight: 400;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .address p {
            margin: 5px 0;
            font-size: 12px;
            color: #4d4d4d;
            line-height: 1.6;
        }
        .footer {
            background-color: #fafafa;
            padding: 25px;
            text-align: center;
            font-size: 12px;
            color: #666666;
            border-top: 1px solid #e5e5e5;
        }
        .footer a {
            color: #1a1a1a;
            text-decoration: none;
            margin: 0 10px;
            letter-spacing: 0.5px;
        }
        .footer a:hover {
            color: #a67c00; /* Golden accent */
        }
        /* Accent color for subtle elegance */
        .accent, h1.accent {
            color: #a67c00; /* Luxurious gold */
        }
        p {
            font-size: 12px;
            color: #4d4d4d;
            line-height: 1.6;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="accent">Oiza Apparels</h1>
            <p>Choose from a collection of high quality fabrics that suit your style</p>
        </div>
        <div class="navbar">
            <a href="https://oiza-apparel.onrender.com/">Home</a>
            <a href="https://oiza-apparel.onrender.com/new-arrivals">New Arrivals</a>
            <a href="https://oiza-apparel.onrender.com/fabrics">Fabrics</a>
            <a href="https://oiza-apparel.onrender.com/shop">Shop</a>
            <a href="https://oiza-apparel.onrender.com/contact">Contact</a>
        </div>
        <div class="content">
            <h2>Thank You for Your Order!</h2>
            <p>Dear {{ $order->shippingAddress->name ?? $order->user->name ?? 'Customer' }},</p>
            <p>We appreciate your purchase at Oiza Apparels. Your order has been confirmed and is being processed.</p>
            
            <div class="order-details">
                <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
                <p><strong>Order Status:</strong> Confirmed</p>
            </div>
            
            <h2>Order Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                    <tr>
                        <td>
                            @if ($item->product->cover_media)
                                <img src="{{ Storage::disk(env('APP_DISK', 'local'))->url($item->product->cover_media) }}" alt="{{ $item->product->name }}">
                            @else
                                <span>No image</span>
                            @endif
                        </td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₦{{ number_format($item->price, 2) }}</td>
                        <td>₦{{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="totals">
                <p><strong>Subtotal:</strong> ₦{{ number_format($order->subtotal ?? $order->total, 2) }}</p>
                <p><strong>Shipping:</strong> ₦{{ number_format($order->shipping ?? 0, 2) }}</p>
                <p><strong>Tax:</strong> ₦{{ number_format($order->tax ?? 0, 2) }}</p>
                <p><strong>Grand Total:</strong> ₦{{ number_format($order->total, 2) }}</p>
            </div>
            
            <div class="address">
                <h3>Shipping Address</h3>
                <p>{{ $order->shippingAddress->name ?? $order->user->name }}</p>
                <p>{{ $order->shippingAddress->address }}</p>
                <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->zip }}</p>
                <p>{{ $order->shippingAddress->country }}</p>
            </div>
            
            {{-- <div class="address">
                <h3>Billing Address</h3>
                <p>{{ $order->billingAddress->name ?? $order->user->name }}</p>
                <p>{{ $order->billingAddress->address }}</p>
                <p>{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->zip }}</p>
                <p>{{ $order->billingAddress->country }}</p>
            </div> --}}
            
            <p>If you have any questions about your order, please contact us at sample@mail.com.ng or via WhatsApp at +2347012345678.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Oiza Apparels. All rights reserved.</p>
            <p><a href="https://oiza-apparel.onrender.com/">Visit our website</a> | <a href="https://wa.me/2348034602165?text=Hello%20Oiza%20Apparels%2C%20I%20have%20a%20question.">Contact Us on WhatsApp</a></p>
        </div>
    </div>
</body>
</html>
