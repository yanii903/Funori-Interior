@extends('admin.layout.admin')
@section('title', 'Order List')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Order List</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Dashboard</div></a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Order List</div>
                    </li>
                </ul>
            </div>
            <!-- order-list -->
            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search" method="get" action="{{ route('admin.orders.index') }}">
                            <fieldset class="name">
                                <input type="text" placeholder="Search order code or customer..." class="" name="q" value="{{ request('q') }}">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="#"><i class="icon-file-text"></i>Export all order</a>
                </div>
                <div class="wg-table table-all-category">
                    <ul class="table-title flex gap20 mb-14">
                        <li><div class="body-title">Product</div></li>
                        <li><div class="body-title">Order ID</div></li>
                        <li><div class="body-title">Price</div></li>
                        <li><div class="body-title">Quantity</div></li>
                        <li><div class="body-title">Payment</div></li>
                        <li><div class="body-title">Status</div></li>
                        <li><div class="body-title">Tracking</div></li>
                        <li><div class="body-title">Action</div></li>
                    </ul>
                    <ul class="flex flex-column">
                        @forelse($orders as $order)
                        <li class="wg-product item-row gap20">
                            <div class="name">
                                <div class="image">
                                    <img src="{{ optional(optional($order->items->first())->product)->image_url ?: asset('images/products/default.jpg') }}" alt="">
                                </div>
                                <div class="title line-clamp-2 mb-0">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="body-text fw-6">
                                        {{ $order->items->first()->product->name ?? 'N/A' }}
                                    </a>
                                </div>
                            </div>
                            <div class="body-text text-main-dark mt-4">#{{ $order->order_code }}</div>
                            <div class="body-text text-main-dark mt-4">${{ number_format($order->total_amount, 2) }}</div>
                            <div class="body-text text-main-dark mt-4">{{ $order->items->sum('quantity') }}</div>
                            <div class="body-text text-main-dark mt-4">
                                {{ $order->paymentMethod->name ?? $order->payment_method ?? 'N/A' }}
                            </div>
                            <div>
                                @if($order->order_status === 'delivered')
                                    <div class="block-available bg-1 fw-7">Success</div>
                                @elseif($order->order_status === 'pending' || $order->order_status === 'pending_confirmation')
                                    <div class="block-pending bg-1 fw-7">Pending</div>
                                @elseif($order->order_status === 'pending_cancellation')
                                    <div class="block-pending fw-7" style="background:#ef4444;color:#fff;">Pending Cancellation</div>
                                @elseif($order->order_status === 'cancelled')
                                    <div class="block-pending bg-1 fw-7" style="background:#f87171">Cancelled</div>
                                @else
                                    <div class="block-pending bg-1 fw-7">{{ ucfirst(str_replace('_',' ',$order->order_status)) }}</div>
                                @endif
                            </div>
                            <div>
                                @if($order->order_status !== 'cancelled')
                                    <a href="{{ route('admin.orders.tracking', $order->id) }}" class="block-tracking bg-1">Tracking</a>
                                @endif
                            </div>
                            <div class="list-icon-function">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="item eye" title="View"><i class="icon-eye"></i></a>
                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="item edit" title="Edit"><i class="icon-edit-3"></i></a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="item trash" type="submit" onclick="return confirm('Delete this order?')" title="Delete"><i class="icon-trash-2"></i></button>
                                </form>
                            </div>
                        </li>
                        @empty
                        <li>
                            <div class="body-text text-center py-4">No orders found.</div>
                        </li>
                        @endforelse
                    </ul>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10">
                    <div class="text-tiny">
                        Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} entries
                    </div>
                    <ul class="wg-pagination">
                        {{ $orders->links() }}
                    </ul>
                </div>
            </div>
            <!-- /order-list -->
        </div>
    </div>
    <div class="bottom-page">
        <div class="body-text">Copyright © 2024 <a href="https://themesflat.co/html/ecomus/index.html">Ecomus</a>. Design by Themesflat All rights reserved</div>
    </div>
@endsection
