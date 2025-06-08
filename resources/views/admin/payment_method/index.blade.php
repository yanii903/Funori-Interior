@extends('admin.layout.admin')

@section('title', 'Danh sách phương thức thanh toán')

@section('content')
 @if (session('success'))
                <div class="alert alert-success mb-3" style="font-size:1.25rem; font-weight:bold;">
                    {{ session('success') }}
                </div>
            @endif
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-30">
            <h3>Payment Methods</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Payment Methods</div></li>
            </ul>
        </div>

        <div style="margin-bottom: 20px;">
            <a class="tf-button style-1 w208" href="{{ route('admin.payment_methods.create') }}">
                <i class="icon-plus"></i> Add new
            </a>
        </div>

        <div class="wg-box">
            <div class="title-box">
                <i class="icon-credit-card"></i>
                <div class="body-text">
                    Manage your payment methods here. You can enable/disable or delete methods as needed.
                </div>
            </div>
            <div class="wg-table table-product-list">
                <ul class="table-title flex gap20 mb-14">
                    <li style="width:18%"><div class="body-title">Name</div></li>
                    <li style="width:15%"><div class="body-title">Code</div></li>
                    <li style="width:30%"><div class="body-title">Description</div></li>
                    <li style="width:12%"><div class="body-title">Status</div></li>
                    <li style="width:25%"><div class="body-title">Action</div></li>
                </ul>
                <ul class="flex flex-column">
                    @foreach($methods as $method)
                    <li class="wg-product item-row gap20" style="align-items:center;">
                        <div class="body-text fw-7" style="width:18%">{{ $method->name }}</div>
                        <div class="body-text" style="width:15%">{{ $method->code }}</div>
                        <div class="body-text" style="width:30%">{{ Str::limit($method->description, 40) }}</div>
                        <div style="width:12%">
                            @if($method->is_active)
                                <span class="block-available bg-1 fw-7">Active</span>
                            @else
                                <span class="block-stock bg-1 fw-7">Inactive</span>
                            @endif
                        </div>
                        <div class="list-icon-function" style="width:25%;display:flex;gap:8px;align-items:center;">
                            <form action="{{ route('admin.payment_methods.toggle', $method->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="tf-button style-1 small">{{ $method->is_active ? 'Disable' : 'Enable' }}</button>
                            </form>
                            <form action="{{ route('admin.payment_methods.destroy', $method->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="tf-button style-2 custom-danger" style="background:#e3342f; border-color:#e3342f; color:#fff;">Delete</button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<style>
    .tf-button.custom-danger {
        background: #e3342f;
        border-color: #e3342f;
        color: #fff;
        transition: background 0.2s, border-color 0.2s;
    }
    .tf-button.custom-danger:hover, .tf-button.custom-danger:focus {
        background: #b91c1c;
        border-color: #b91c1c;
        color: #fff;
    }
</style>
@endsection
