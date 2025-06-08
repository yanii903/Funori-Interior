@extends('admin.layout.admin')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 fs-3">Chi tiết sản phẩm</h2>
    <div class="row g-4">
        <!-- Ảnh sản phẩm -->
        <div class="col-md-4">
            @if($product->images->count())
                <div class="mb-3">
                    <img src="{{ asset($product->images->first()->image_url) }}" alt="Ảnh sản phẩm" class="img-fluid rounded shadow-sm mb-2" style="max-height:240px;object-fit:cover;">
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($product->images as $img)
                        <img src="{{ asset($img->image_url) }}" alt="Ảnh phụ" class="img-thumbnail" style="width:60px;height:60px;object-fit:cover;">
                    @endforeach
                </div>
            @else
                <div class="bg-light text-center py-5 rounded mb-3 text-muted fs-5">Không có ảnh</div>
            @endif
        </div>
        <!-- Thông tin sản phẩm -->
        <div class="col-md-8 fs-5">
            <h3 class="mb-2 fs-4">{{ $product->name }}</h3>
            <div class="mb-2 text-muted fs-6">{{ $product->slug }}</div>
            <div class="row mb-2">
                <div class="col-6"><strong>Danh mục:</strong> {{ $product->category->name ?? '-' }}</div>
                <div class="col-6"><strong>Thương hiệu:</strong> {{ $product->brand->name ?? '-' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-6"><strong>Giá gốc:</strong> <span class="text-danger fw-bold">{{ number_format($product->regular_price, 0, ',', '.') }} đ</span></div>
                <div class="col-6"><strong>Tổng kho:</strong> <span class="fw-bold">{{ $product->variants->sum('stock_quantity') }}</span></div>
            </div>
            <div class="row mb-2">
                <div class="col-6">
                    <strong>Trạng thái:</strong>
                    @if($product->status == 'published')
                        <span class="badge bg-success fs-6">Hiển thị</span>
                    @elseif($product->status == 'draft')
                        <span class="badge bg-secondary fs-6">Nháp</span>
                    @elseif($product->status == 'archived')
                        <span class="badge bg-dark fs-6">Lưu trữ</span>
                    @else
                        <span class="badge bg-danger fs-6">Hết hàng</span>
                    @endif
                </div>
                <div class="col-6">
                    <strong>Nổi bật:</strong>
                    @if($product->is_featured)
                        <span class="badge bg-warning text-dark fs-6">Nổi bật</span>
                    @else
                        <span class="text-muted">Không</span>
                    @endif
                </div>
            </div>
            <div class="mb-2"><strong>Mô tả:</strong></div>
            <div class="border rounded p-3 bg-light mb-3 fs-6">{!! nl2br(e($product->description)) !!}</div>

            @if(isset($product->variants) && count($product->variants))
                <div class="mb-3">
                    <h5 class="mb-2 fs-5">Các biến thể</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle mb-0 fs-6">
                            <thead class="table-light">
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Giá</th>
                                    <th>Kho</th>
                                    <th>Thuộc tính</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->variants as $variant)
                                    <tr>
                                        <td>
                                            @if($variant->image)
                                                <img src="{{ asset($variant->image->image_url) }}" alt="Ảnh variant" class="rounded" style="width:40px;height:40px;object-fit:cover;">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $gia = $product->regular_price * (1 + ($variant->price_modifier ?? 0) / 100);
                                            @endphp
                                            {{ number_format($gia, 0, ',', '.') }} đ
                                        </td>
                                        <td>{{ $variant->stock_quantity ?? '-' }}</td>
                                        <td>
                                            @if(isset($variant->attributeValues) && count($variant->attributeValues))
                                                @foreach($variant->attributeValues as $attrVal)
                                                    <span class="badge bg-info text-dark me-1">{{ $attrVal->attribute->name }}: {{ $attrVal->value }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary fs-5">Sửa</a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary fs-5">Quay lại danh sách</a>
            </div>
        </div>
    </div>
</div>
@endsection