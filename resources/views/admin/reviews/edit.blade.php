@extends('admin.layout.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Edit Review</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <a href="{{ route('admin.reviews.index') }}">
                            <div class="text-tiny">Reviews</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Edit Review</div>
                    </li>
                </ul>
            </div>
            <!-- form-edit-review -->
            <form class="form-edit-review" method="POST" action="{{ route('admin.reviews.update', $review->id) }}">
                @csrf
                @method('PUT')
                <div class="wg-box mb-30">
                    <fieldset>
                        <div class="body-title mb-10">User</div>
                        <input type="text" class="form-control" value="{{ $review->user->full_name ?? 'Unknown' }}"
                            readonly>
                    </fieldset>
                    <fieldset>
                        <div class="body-title mb-10">Product</div>
                        <input type="text" class="form-control" value="{{ $review->product->name ?? 'Unknown' }}"
                            readonly>
                    </fieldset>
                    <fieldset>
                        <div class="body-title mb-10">Order Code</div>
                        @php
                            $orderId = $review->orderItem->order_id ?? null;
                            $order = $orders->firstWhere('id', $orderId);
                        @endphp
                        <input type="text" class="form-control"
                            value="{{ $order ? $order->order_code : $orderId ?? 'N/A' }}" readonly>
                    </fieldset>
                    <fieldset>
                        <div class="body-title mb-10">Rating</div>
                        <div class="d-flex align-items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <span style="color: orange; font-size: 22px;">&#9733;</span>
                                @else
                                    <span style="color: lightgray; font-size: 22px;">&#9733;</span>
                                @endif
                            @endfor
                            <span class="ms-2 text-muted">({{ $review->rating }})</span>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="body-title mb-10">Comment</div>
                        <input type="text" name="comment" class="form-control" min="1" max="5"
                            value="{{ old('comment', $review->comment) }}" readonly>
                    </fieldset>
                    <fieldset>
                        <div class="body-title mb-10">Status*</div>
                        <select name="status">
                            <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                        </select>
                    </fieldset>
                    <fieldset>
                        <div class="body-title mb-10">Created At</div>
                        <input type="text" class="form-control" value="{{ $review->created_at->format('d-m-Y H:i') }}"
                            readonly>
                    </fieldset>
                </div>
                <div class="cols gap10">
                    <button class="tf-button w380" type="submit">Update Review</button>
                    <a href="{{ route('admin.reviews.index') }}" class="tf-button style-3 w380">Cancel</a>
                </div>
            </form>
            <!-- /form-edit-review -->
        </div>
    </div>
@endsection
