{{-- filepath: c:\laragon\www\Funori-main\resources\views\admin\categories\create.blade.php --}}
@extends('admin.layout.admin')

@section('content')
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-30">
            <h3>Thêm danh mục</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}">
                        <div class="text-tiny">Category</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Add Category</div>
                </li>
            </ul>
        </div>
        <!-- new-category -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.categories.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <fieldset class="name">
                    <div class="body-title">Name Category <span class="tf-color-1">*</span></div>
                    <input class="flex-grow form-control @error('name') is-invalid @enderror" type="text"
                        placeholder="Name Category" name="name" id="name" value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                    @enderror
                </fieldset>
                <input type="hidden" name="slug" id="slug" value="{{ old('slug') }}">
                <fieldset class="category">
                    <div class="body-title">Danh mục cha</div>
                    <div class="select flex-grow">
                        <select name="parent_id" id="parent_id" class="@error('parent_id') is-invalid @enderror">
                            <option value="">-- Không có --</option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
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
                    <textarea class="flex-grow" name="description" id="description" placeholder="Mô tả danh mục">{{ old('description') }}</textarea>
                </fieldset>
                <fieldset>
                    <div class="body-title">Ảnh <span class="tf-color-1">*</span></div>
                    <div class="upload-image flex-grow d-block">
                        <div class="item up-load">
                            <label class="uploadfile h250" for="image_url">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Kéo thả ảnh vào đây hoặc <span class="tf-color">nhấn để
                                        chọn</span></span>
                                <img id="image_url-preview" src="#" alt="" style="display: none;">
                                <input type="file" id="image_url" name="image_url"
                                    class="@error('image_url') is-invalid @enderror" accept="image/*">
                            </label>
                        </div>
                        @error('image_url')
                            <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset>
                <fieldset class="category">
                    <div class="body-title">Trạng thái <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="is_active" id="is_active" class="@error('is_active') is-invalid @enderror">
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Không kích hoạt</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset>
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Tạo</button>
                    <a href="{{ route('admin.categories.index') }}" class="tf-button w208"
                        style="background-color: #6c757d;">Quay lại</a>
                </div>
            </form>
        </div>
        <!-- /new-category -->
    </div>
    <script>
        // Tạo slug tự động khi nhập tên
        document.getElementById('name').addEventListener('input', function() {
            let name = this.value;
            let slug = name.toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // bỏ dấu tiếng Việt
                .replace(/[^a-z0-9\s-]/g, '') // bỏ ký tự đặc biệt
                .trim().replace(/\s+/g, '-'); // thay khoảng trắng bằng -
            document.getElementById('slug').value = slug;
        });

        // Hiển thị ảnh xem trước khi tải lên
    </script>
@endsection
