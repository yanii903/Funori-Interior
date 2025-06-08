@extends('admin.layout.admin')
@section('title', 'Thêm phương thức giao hàng')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-30">
            <h3>Thêm phương thức giao hàng</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('admin.shipping_methods.index') }}">
                        <div class="text-tiny">Shipping Methods</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Thêm phương thức</div></li>
            </ul>
        </div>
        <div class="wg-box">
            <div class="title-box mb-20">
                <i class="icon-truck"></i>
                <div class="body-text">Điền thông tin phương thức giao hàng mới vào form bên dưới.</div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
              @if (session('success'))
                <div class="alert alert-success mb-3" style="font-size:1.25rem; font-weight:bold;">
                    {{ session('success') }}
                </div>
            @endif
            <form method="POST" action="{{ route('admin.shipping_methods.store') }}" class="form-grid">
                @csrf
                <div class="form-group mb-3">
                    <label for="name" class="form-label label-lg">Tên phương thức <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Tên phương thức" value="{{ old('name') }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="description" class="form-label label-lg">Mô tả</label>
                    <textarea name="description" id="description" class="form-control" placeholder="Mô tả">{{ old('description') }}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="cost" class="form-label label-lg">Chi phí <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="cost" id="cost" class="form-control" placeholder="Chi phí" value="{{ old('cost') }}" required>
                </div>
                <div class="form-group mt-4 d-flex align-items-center gap-2">
                    <button type="submit" class="tf-button style-1">Thêm phương thức</button>
                    <a href="{{ route('admin.shipping_methods.index') }}" class="tf-button style-1">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .label-lg {
        font-size: 1.15rem;
        font-weight: bold;
        color: #222;
        margin-bottom: 6px;
        display: block;
    }
     .form-control, .form-control textarea {
        font-size: 1.5rem;
    }
</style>
@endsection