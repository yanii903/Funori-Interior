@extends('admin.layout.admin')

@section('title', 'Danh sách thuộc tính')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-30">
    <h3>All Attributes</h3>
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
                <div class="text-tiny">Attributes</div>
            </a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <div class="text-tiny">All Attributes</div>
        </li>
    </ul>
</div>
<!-- all-attribute -->
<div class="wg-box">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
            <form class="form-search" method="GET" action="{{ route('admin.attributes.index') }}">
                <fieldset class="name">
                    <input type="text" placeholder="Search here..." class="" name="name" tabindex="2"
                        value="{{ request('name') }}">
                </fieldset>
                <div class="button-submit">
                    <button class="" type="submit"><i class="icon-search"></i></button>
                </div>
            </form>
        </div>
        <a class="tf-button style-1 w208" href="add-attributes.html"><i class="icon-plus"></i>Add new</a>
    </div>
    <div class="wg-table table-all-attribute">
        <ul class="table-title flex gap20 mb-14">
            <li>
                <div class="body-title">Category</div>
            </li>
            <li>
                <div class="body-title">Value</div>
            </li>
            <li>
                <div class="body-title">Action</div>
            </li>
        </ul>
        <ul class="flex flex-column">
            @foreach($attributes as $attributeValue)
            <li class="attribute-item item-row flex items-center justify-between gap20">
                <div class="name">
                    <span class="body-title-2">{{ $attributeValue->attribute->name ?? 'N/A' }}</span>
                </div>
                <div class="body-text">{{ $attributeValue->value }}</div>
                <div class="list-icon-function">
                    <div class="item edit">
                        <a href="{{ route('admin.attributes.edit', $attributeValue->id) }}"><i class="icon-edit-3"></i></a>
                    </div>
                    <div class="item trash">
                        <form action="{{ route('admin.attributes.destroy', $attributeValue->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:transparent;border:none;padding:0;">
                                <i class="icon-trash-2"></i>
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
            Showing {{ $attributes->firstItem() }} to {{ $attributes->lastItem() }} of {{ $attributes->total() }} entries
        </div>
        <ul class="wg-pagination">
            <li>
                @if ($attributes->onFirstPage())
                <span><i class="icon-chevron-left"></i></span>
                @else
                <a href="{{ $attributes->previousPageUrl() }}"><i class="icon-chevron-left"></i></a>
                @endif
            </li>
            @for ($page = 1; $page <= $attributes->lastPage(); $page++)
                <li class="{{ $page == $attributes->currentPage() ? 'active' : '' }}">
                    @if ($page == $attributes->currentPage())
                    <a href="#">{{ $page }}</a>
                    @else
                    <a href="{{ $attributes->url($page) }}">{{ $page }}</a>
                    @endif
                </li>
                @endfor
                <li>
                    @if ($attributes->hasMorePages())
                    <a href="{{ $attributes->nextPageUrl() }}"><i class="icon-chevron-right"></i></a>
                    @else
                    <span><i class="icon-chevron-right"></i></span>
                    @endif
                </li>
        </ul>
    </div>
</div>



@endsection