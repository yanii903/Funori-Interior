<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shipping Note #{{ $order->order_code }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .shipping-box { max-width: 800px; margin: auto; border:1px solid #eee; padding:30px; }
        .title { font-size: 32px; font-weight: bold; margin-bottom: 20px; }
        .info, .items { margin-bottom: 20px; }
        .items table { width: 100%; border-collapse: collapse; }
        .items th, .items td { border: 1px solid #eee; padding: 8px; }
        .items th { background: #f5f5f5; }
        .footer { margin-top: 40px; text-align: center; color: #888; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
<div class="shipping-box">
    <div class="title">SHIPPING NOTE</div>
    <div class="info">
        <b>Order ID:</b> #{{ $order->order_code }}<br>
        <b>Date:</b> {{ $order->ordered_at ? $order->ordered_at->format('d/m/Y H:i') : '-' }}<br>
        <b>Customer:</b> {{ $order->customer_name }}<br>
        <b>Phone:</b> {{ $order->customer_phone }}<br>
        <b>Shipping Address:</b> {{ $order->shipping_address }}
    </div>
    <div class="items">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="footer">
        <button class="no-print" onclick="window.print()">In phiếu giao hàng</button>
        <div>Chúc quý khách nhận hàng thành công!</div>
    </div>
</div>
</body>
</html>
