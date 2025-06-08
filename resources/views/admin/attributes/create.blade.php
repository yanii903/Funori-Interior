@extends('admin.layout.admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-30">
    <h3>Add Attribute</h3>
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
            <div class="text-tiny">Add Attribute</div>
        </li>
    </ul>
</div>
<div class="wg-box">
    <form action="{{ route('admin.attributes.store') }}" method="POST">
        @csrf
        <fieldset class="name">
            <div class="body-title">Attribute</div>
            <select name="attribute_id" id="attribute_id" class="form-control" required>
                <option value="">-- Select Attribute --</option>
                @foreach($attributes as $attribute)
                <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                @endforeach
            </select>
        </fieldset>
        <fieldset class="name">
            <div class="body-title">Value</div>
            <input class="flex-grow" type="text" placeholder="Attribute value" name="value" tabindex="0" value="" aria-required="true" required="">
        </fieldset>
        <div class="bot">
            <div></div>
            <button class="tf-button w208" type="submit">Create</button>
            <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">Back</a>

        </div>
    </form>
</div>

@endsection