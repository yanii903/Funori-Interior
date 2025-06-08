{{-- filepath: c:\laragon\www\Funori-main\resources\views\admin\categories\edit.blade.php --}}
@extends('admin.layout.admin')

@section('content')
    <div class="main-content-wrap">
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.categories.update', $category->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <fieldset class="name">
                    <div class="body-title">Tên danh mục <span class="tf-color-1">*</span></div>
                    <input class="flex-grow form-control @error('name') is-invalid @enderror" type="text"
                        placeholder="Tên danh mục" name="name" id="name" value="{{ old('name', $category->name) }}">
                    @error('name')
                        <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                    @enderror
                </fieldset>

                <input type="hidden" name="slug" id="slug" value="{{ old('slug', $category->slug) }}">

                <fieldset class="category">
                    <div class="body-title">Danh mục cha</div>
                    <div class="select flex-grow">
                        <select name="parent_id" id="parent_id" class="@error('parent_id') is-invalid @enderror">
                            <option value="">-- Không có --</option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}"
                                    {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset>

                <fieldset>
                    <div class="body-title">Mô tả</div>
                    <textarea class="flex-grow" name="description" id="description" placeholder="Mô tả danh mục">{{ old('description', $category->description) }}</textarea>
                </fieldset>

                <fieldset>
                    <div class="body-title">Ảnh</div>
                    <div class="upload-image flex-grow d-block">
                        <div class="item up-load" style="display: flex; align-items: flex-start; gap: 24px;">
                            <label class="uploadfile h250" for="image_url" style="flex:1;">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Kéo thả ảnh vào đây hoặc <span class="tf-color">nhấn để
                                        chọn</span></span>
                                <img id="image_url-preview" src="#" alt=""
                                    style="display: none; max-width:120px; margin-top:10px;">
                                <input type="file" id="image_url" name="image_url"
                                    class="@error('image_url') is-invalid @enderror" accept="image/*">
                            </label>
                        </div>
                        @error('image_url')
                            <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset>
                
                {{-- Ảnh cũ --}}
                @if ($category->image_url)
                    <div id="old-image-wrap" style="text-align:center;">
                        <div style="font-size:13px; color:#888;">Ảnh hiện tại</div>
                        <img id="old-image" src="{{ asset('storage/' . $category->image_url) }}" alt="Ảnh danh mục"
                            style="max-width: 120px; margin-top:10px;">
                    </div>
                @endif

                <fieldset class="category">
                    <div class="body-title">Trạng thái <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="is_active" id="is_active" class="@error('is_active') is-invalid @enderror">
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="1" {{ old('is_active', $category->is_active) == '1' ? 'selected' : '' }}>
                                Kích hoạt</option>
                            <option value="0" {{ old('is_active', $category->is_active) == '0' ? 'selected' : '' }}>
                                Không kích hoạt</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset>

                <fieldset>
                    <div class="body-title">Ngày tạo</div>
                    <input type="text" class="form-control" value="{{ $category->created_at }}" readonly>
                </fieldset>
                <fieldset>
                    <div class="body-title">Ngày cập nhật</div>
                    <input type="text" class="form-control" value="{{ $category->updated_at }}" readonly>
                </fieldset>

                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Cập nhật</button>
                    <a href="{{ route('admin.categories.index') }}" class="tf-button w208"
                        style="background-color: #6c757d;">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Tạo slug tự động khi nhập tên
        document.getElementById('name').addEventListener('input', function() {
            let name = this.value;
            let slug = name.toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9\s-]/g, '')
                .trim().replace(/\s+/g, '-');
            document.getElementById('slug').value = slug;
        });

        // Hiển thị ảnh xem trước khi tải lên
    </script>
@endsection
