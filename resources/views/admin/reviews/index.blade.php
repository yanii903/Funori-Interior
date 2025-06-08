@extends('admin.layout.admin')

@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Reviews List</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="index.html">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="#">
                            <div class="text-tiny">Reviews</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Reviews List</div>
                    </li>
                </ul>
            </div>
            <!-- order-list -->
            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search flex gap10" method="GET">
                            <fieldset class="name">
                                <input type="text" placeholder="Search user or product..." name="keyword"
                                    value="{{ request('keyword') }}">
                            </fieldset>
                            <fieldset>
                                <select name="status">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                        Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                            </fieldset>
                            <div class="button-submit">
                                <button type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="#">
                        {{-- <a class="tf-button style-1 w208" href="{{ route('admin.reviews.export') }}"> --}}
                        <i class="icon-file-text"></i>Export all reviews
                    </a>
                </div>
                <div class="wg-table table-all-category">
                    <ul class="table-title flex gap20 mb-14">
                        <li>
                            <div class="body-title">Order Code</div>
                        </li>
                        <li>
                            <div class="body-title">User Name</div>
                        </li>
                        <li>
                            <div class="body-title">Product Name</div>
                        </li>
                        <li>
                            <div class="body-title">Rating</div>
                        </li>
                        <li>
                            <div class="body-title">Comment</div>
                        </li>
                        <li>
                            <div class="body-title">Status</div>
                        </li>
                        <li>
                            <div class="body-title">Quantity</div>
                        </li>
                        <li>
                            <div class="body-title">Subtotal</div>
                        </li>
                        <li>
                            <div class="body-title">Action</div>
                        </li>
                    </ul>
                    <ul class="flex flex-column">
                        @foreach ($reviews as $value)
                            <li class="wg-product item-row gap20">
                                {{-- Order Code --}}
                                <div class="body-text text-main-dark mt-4">
                                    @php
                                        // Lấy order_id từ orderItem (nếu có quan hệ)
                                        $orderId = $value->orderItem->order_id ?? null;
                                        $order = $orders->firstWhere('id', $orderId);
                                    @endphp
                                    {{ $order ? $order->order_code : $orderId ?? 'N/A' }}
                                </div>
                                {{-- User Name --}}
                                <div class="body-text text-main-dark mt-4">
                                    {{ $value->user->full_name ? $value->user->full_name : $value->user->full_name ?? $value->user->id }}
                                </div>
                                {{-- Product Name --}}
                                <div class="body-text text-main-dark mt-4">
                                    {{ $value->product ? $value->product->name : $value->product_name ?? $value->product_id }}
                                </div>
                                {{-- Rating --}}
                                <div class="body-text text-main-dark mt-4">
                                    <span class="badge" style="background: #ffc107; color: #111; font-weight: bold;">
                                        ★ {{ $value->rating ?? 'N/A' }}
                                    </span>
                                </div>
                                {{-- Comment --}}
                                <div class="body-text text-main-dark mt-4">
                                    (View Detail)
                                </div>
                                {{-- Status --}}
                                <div class="body-text text-main-dark mt-4">
                                    @php
                                        $status = strtolower($value->status ?? '');
                                        $statusColor = match ($status) {
                                            'approved' => '#28a745',
                                            'pending' => '#ffc107',
                                            'rejected' => '#dc3545',
                                            default => '#6c757d',
                                        };
                                    @endphp
                                    <span class="badge"
                                        style="background: {{ $statusColor }}; color: #fff; font-weight: bold;">
                                        {{ ucfirst($value->status ?? 'N/A') }}
                                    </span>
                                </div>
                                {{-- Quantity --}}
                                <div class="body-text text-main-dark mt-4">
                                    {{ $value->orderItem->quantity ?? 'N/A' }}
                                </div>
                                {{-- Subtotal --}}
                                <div class="body-text text-main-dark mt-4">
                                    {{ isset($value->orderItem->subtotal) ? number_format($value->orderItem->subtotal, 2) : 'N/A' }}
                                </div>
                                {{-- Action --}}
                                <div class="list-icon-function">
                                    <div class="list-icon-function">
                                        <div class="item eye" data-bs-toggle="modal"
                                            data-bs-target="#quickViewModal{{ $value->id }}">
                                            <i class="icon-eye"></i>
                                        </div>
                                        <!-- Modal Quick View Review -->
                                        <div class="modal fade" id="quickViewModal{{ $value->id }}" tabindex="-1"
                                            aria-labelledby="quickViewLabel{{ $value->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content shadow-lg rounded-4 border-0"
                                                    style="font-size: 16px;">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h3 class="modal-title fw-bold mb-0 text-light"
                                                            id="quickViewLabel{{ $value->id }}">
                                                            <i class="bi bi-info-circle me-2"></i>Review Details
                                                        </h3>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                    </div>
                                                    <div class="modal-body px-5 py-4">
                                                        <div class="row gy-3">
                                                            <div class="col-sm-6">
                                                                <strong>Order Code:</strong>
                                                                <div class="text-muted">
                                                                    @php
                                                                        $orderId = $value->orderItem->order_id ?? null;
                                                                        $order = $orders->firstWhere('id', $orderId);
                                                                    @endphp
                                                                    {{ $order ? $order->order_code : $orderId ?? 'N/A' }}
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <strong>User:</strong>
                                                                <div class="text-muted">
                                                                    {{ $value->user->full_name ?? 'Unknown' }}
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <strong>Product:</strong>
                                                                <div class="text-muted">
                                                                    {{ $value->product->name ?? $value->product_name ?? 'Unknown' }}
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <strong>Quantity:</strong>
                                                                <div class="text-muted">
                                                                    {{ $value->orderItem->quantity ?? 'N/A' }}
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <strong>Subtotal:</strong>
                                                                <div class="text-muted">
                                                                    {{ isset($value->orderItem->subtotal) ? number_format($value->orderItem->subtotal, 2) : 'N/A' }}
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <strong>Rating:</strong>
                                                                <div class="text-muted">
                                                                    <span class="badge"
                                                                        style="background: #ffc107; color: #111; font-weight: bold;">
                                                                        ★ {{ $value->rating ?? 'N/A' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <strong>Comment:</strong>
                                                                <div class="border rounded p-3 bg-light text-secondary fst-italic">
                                                                    {{ $value->comment ?? '(No comment)' }}
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <strong>Status:</strong>
                                                                @php
                                                                    $status = strtolower($value->status ?? '');
                                                                    $statusColor = match ($status) {
                                                                        'approved' => '#28a745',
                                                                        'pending' => '#ffc107',
                                                                        'rejected' => '#dc3545',
                                                                        default => '#6c757d',
                                                                    };
                                                                @endphp
                                                                <span class="badge"
                                                                    style="background: {{ $statusColor }}; color: #fff; font-weight: bold;">
                                                                    {{ ucfirst($value->status ?? 'N/A') }}
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <strong>Created At:</strong>
                                                                <div class="text-muted">
                                                                    {{ $value->created_at->format('d-m-Y H:i') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="item edit">
                                            <a href="{{ route('admin.reviews.edit', $value->id) }}"><i
                                                    class="icon-edit-3"></i></a>
                                        </div>
                                        <div class="item trash">
                                            <form action="{{ route('admin.reviews.destroy', $value->id) }}"
                                                method="POST" style="display:inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    style="background: none; border: none; padding: 0; color: inherit; cursor: pointer; display: flex; align-items: center;">
                                                    <i class="icon-trash-2" style="color: red; font-size: 20px;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10">
                    <div class="text-tiny">
                        Hiển thị {{ $reviews->firstItem() }} đến {{ $reviews->lastItem() }} trong tổng số
                        {{ $reviews->total() }} bản ghi
                    </div>

                    <ul class="wg-pagination">
                        {{-- Previous Page Link --}}
                        <li class="{{ $reviews->onFirstPage() ? 'disabled' : '' }}">
                            <a href="{{ $reviews->previousPageUrl() ?? '#' }}">
                                <i class="icon-chevron-left"></i>
                            </a>
                        </li>

                        {{-- Pagination Elements --}}
                        @for ($i = 1; $i <= $reviews->lastPage(); $i++)
                            <li class="{{ $reviews->currentPage() == $i ? 'active' : '' }}">
                                <a href="{{ $reviews->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Next Page Link --}}
                        <li class="{{ $reviews->hasMorePages() ? '' : 'disabled' }}">
                            <a href="{{ $reviews->nextPageUrl() ?? '#' }}">
                                <i class="icon-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
            <!-- /order-list -->
        </div>
        <!-- /main-content-wrap -->
    </div>
@endsection
