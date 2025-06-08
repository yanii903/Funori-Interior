@php
    $allStatuses = [
        'pending_confirmation' => 'Pending Confirmation',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
        'returned' => 'Returned',
    ];
@endphp

@if(empty($transitions[$currentStatus]))
    <div class="alert alert-info mb-0">Không thể chuyển tiếp trạng thái.</div>
@else
    <select name="order_status" id="order_status" class="form-select" style="height:44px; border-radius:8px;" required>
        @foreach($transitions[$currentStatus] as $status)
            <option value="{{ $status }}" @if($order->order_status == $status) selected @endif>
                {{ $allStatuses[$status] ?? ucfirst($status) }}
            </option>
        @endforeach
    </select>
@endif
