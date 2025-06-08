<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_code }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .invoice-box { max-width: 800px; margin: auto; border:1px solid #eee; padding:30px; }
        .title { font-size: 32px; font-weight: bold; margin-bottom: 20px; }
        .info, .items, .totals { margin-bottom: 20px; }
        .items table, .totals table { width: 100%; border-collapse: collapse; }
        .items th, .items td, .totals th, .totals td { border: 1px solid #eee; padding: 8px; }
        .items th { background: #f5f5f5; }
        .totals td { text-align: right; }
        .totals th { text-align: left; }
        .footer { margin-top: 40px; text-align: center; color: #888; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
<div class="invoice-box">
    <div class="title">INVOICE</div>
    <div class="info">
        <b>Order ID:</b> #{{ $order->order_code }}<br>
        <b>Date:</b> {{ $order->ordered_at ? $order->ordered_at->format('d/m/Y H:i') : '-' }}<br>
        <b>Customer:</b> {{ $order->customer_name }}<br>
        <b>Email:</b> {{ $order->customer_email }}<br>
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
    <div class="totals">
        <table>
            <tr>
                <th>Subtotal:</th>
                <td>${{ number_format($order->subtotal_amount, 2) }}</td>
            </tr>
            <tr>
                <th>Shipping:</th>
                <td>${{ number_format($order->shipping_fee, 2) }}</td>
            </tr>
            @if($order->discount_amount)
            <tr>
                <th>Discount:</th>
                <td>- ${{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <th>Tax (GST):</th>
                <td>${{ number_format($order->tax_amount, 2) }}</td>
            </tr>
            <tr>
                <th>Total:</th>
                <td><b>${{ number_format($order->total_amount, 2) }}</b></td>
            </tr>
        </table>
    </div>
    <div class="footer">
        <button class="no-print" onclick="window.print()">In hóa đơn</button>
        <div>Cảm ơn quý khách!</div>
    </div>
</div>
</body>
</html>
