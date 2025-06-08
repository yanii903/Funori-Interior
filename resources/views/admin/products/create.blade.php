@extends('admin.layout.admin')

@section('title', 'Danh sách sản phẩm')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-30">
    <h3>Add Product</h3>
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
                <div class="text-tiny">Product</div>
            </a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <div class="text-tiny">Add Product</div>
        </li>
    </ul>
</div>
<!-- form-add-product -->
<form class="form-add-product" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="wg-box mb-30">
        <fieldset>
            <div class="body-title mb-10">Upload images</div>
            <div class="upload-image mb-16" id="drop-area" style="border:2px dashed #ccc; border-radius:8px;">
                <div class="up-load">
                    <label class="uploadfile" for="myFile" style="width:100%;cursor:pointer;">
                        <span class="icon">
                            <i class="icon-upload-cloud"></i>
                        </span>
                        <div class="text-tiny">
                            Drop your images here or select <span class="text-secondary">click to browse</span>
                        </div>
                        <input type="file" id="myFile" name="images[]" multiple required style="display:none;">
                    </label>
                </div>
                <div class="flex gap20 flex-wrap" id="gallery">
                </div>
            </div>
        </fieldset>
    </div>
    <div class="wg-box mb-30">
        <fieldset class="name">
            <div class="body-title mb-10">Product title <span class="tf-color-1">*</span></div>
            <input class="mb-10" type="text" placeholder="Enter title" name="name" maxlength="100" required>
        </fieldset>
        <fieldset class="category">
            <div class="body-title mb-10">Category <span class="tf-color-1">*</span></div>
            <select name="category_id" required>
                <option value="">-- Choose category --</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </fieldset>
        <fieldset class="brand">
            <div class="body-title mb-10">Brand <span class="tf-color-1">*</span></div>
            <select name="brand_id" required>
                <option value="">-- Choose brand --</option>
                @foreach($brands as $brand)
                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </fieldset>
        <fieldset class="price">
            <div class="body-title mb-10">Price <span class="tf-color-1">*</span></div>
            <input type="number" name="regular_price" min="0" step="0.01" required>
        </fieldset>
        <fieldset class="short_description">
            <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
            <textarea name="short_description" maxlength="255" required></textarea>
        </fieldset>
        <fieldset class="description">
            <div class="body-title mb-10">Description</div>
            <textarea name="description"></textarea>
        </fieldset>

        <!-- VARIANTS -->
        <fieldset class="variants">
            <div class="body-title mb-10">Variants</div>
            <div id="variant-list">
            </div>
            <button type="button" class="tf-button style-1 mt-10" id="add-variant-btn">
                <i class="icon-plus"></i> Add Variant
            </button>
        </fieldset>
    </div>
    <div class="cols gap10">
        <button class="tf-button w380" type="submit">Add product</button>
        <a href="{{ route('admin.products.index') }}" class="tf-button style-3 w380">Cancel</a>
    </div>
</form>


@php
    $attributeSelects = '';
    foreach($attributes as $attribute) {
        $attributeSelects .= '<select name="VARIANT_NAME[attribute_values]['.$attribute->id.']" required>';
        $attributeSelects .= '<option value="">-- '.$attribute->name.' --</option>';
        foreach($attribute->values as $value) {
            $attributeSelects .= '<option value="'.$value->id.'">'.$value->value.'</option>';
        }
        $attributeSelects .= '</select>';
    }

    $imageOptions = '<option value="">-- Ảnh variant (chọn từ gallery) --</option>';
    foreach($productImages as $img) {
        $imageOptions .= '<option value="'.$img->id.'">Ảnh #'.$img->id.'</option>';
    }
@endphp

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropArea = document.getElementById('drop-area');
    const input = document.getElementById('myFile');
    const gallery = document.getElementById('gallery');
    let filesArray = [];

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, e => e.preventDefault(), false);
        dropArea.addEventListener(eventName, e => e.stopPropagation(), false);
    });
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.style.borderColor = '#007bff', false);
    });
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.style.borderColor = '#ccc', false);
    });
    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = Array.from(dt.files);
        filesArray = filesArray.concat(files);
        updateInputFiles();
        previewFiles(filesArray);
    }
    dropArea.querySelector('label').onclick = () => input.click();
    input.addEventListener('change', function() {
        const files = Array.from(this.files);
        filesArray = filesArray.concat(files);
        updateInputFiles();
        previewFiles(filesArray);
    });
    function updateInputFiles() {
        const dataTransfer = new DataTransfer();
        filesArray.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
    }
    function previewFiles(files) {
        gallery.innerHTML = '';
        files.forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'item';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '80px';
                img.style.maxHeight = '80px';
                img.style.objectFit = 'cover';
                img.style.border = '1px solid #eee';
                img.style.borderRadius = '4px';
                div.appendChild(img);
                gallery.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    const variantList = document.getElementById('variant-list');
    const addVariantBtn = document.getElementById('add-variant-btn');
    let variantIndex = 0;

    const attributeSelectsTemplate = `{!! addslashes($attributeSelects) !!}`;
    const imageOptionsTemplate = `{!! addslashes($imageOptions) !!}`;

    addVariantBtn.addEventListener('click', function() {
        const variantDiv = document.createElement('div');
        variantDiv.className = 'variant-row flex gap10 mb-2';
        let selects = attributeSelectsTemplate.replace(/VARIANT_NAME/g, `variants[${variantIndex}]`);
        variantDiv.innerHTML = `
            ${selects}
            <input type="number" name="variants[${variantIndex}][price_modifier]" placeholder="Giá chênh lệch" step="0.01" style="width:200px;">
            <input type="number" name="variants[${variantIndex}][stock_quantity]" placeholder="Kho" min="0" style="width:100px;">
            <input type="file" name="variants[${variantIndex}][image]" accept="image/*" style="width:180px;">
            <button type="button" class="remove-variant tf-button style-3" style="padding:0 8px;">&times;</button>
        `;
        variantList.appendChild(variantDiv);

        variantDiv.querySelector('.remove-variant').onclick = function() {
            variantDiv.remove();
        };
        variantIndex++;
    });
});
</script>
@endsection