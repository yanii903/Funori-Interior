{{-- filepath: c:\laragon\www\Funori-main\resources\views\admin\pages\edit.blade.php --}}
@extends('admin.layout.admin')

@section('title', 'Chỉnh sửa trang')

@section('content')
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-30">
            <h3>Chỉnh sửa trang</h3>
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
                    <a href="{{ route('admin.pages.index') }}">
                        <div class="text-tiny">Page</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Edit Page</div>
                </li>
            </ul>
        </div>
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.pages.update', $page->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <fieldset class="name">
                    <div class="body-title">Tiêu đề trang <span class="tf-color-1">*</span></div>
                    <input class="flex-grow form-control @error('title') is-invalid @enderror" type="text"
                        placeholder="Tiêu đề trang" name="title" id="title" value="{{ old('title', $page->title) }}">
                    @error('title')
                        <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                    @enderror
                </fieldset>
                <input type="hidden" name="slug" id="slug" value="{{ old('slug', $page->slug) }}">
                <fieldset>
                    <div class="body-title">Nội dung <span class="tf-color-1">*</span></div>
                    <textarea class="flex-grow @error('content') is-invalid @enderror" name="content" id="content"
                        rows="6" placeholder="Nội dung trang">{{ old('content', $page->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                    @enderror
                </fieldset>
                <fieldset>
                    <div class="body-title">Tác giả <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="author_id" id="author_id" class="@error('author_id') is-invalid @enderror">
                            <option value="">-- Chọn tác giả --</option>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}"
                                    {{ old('author_id', $page->author_id) == $author->id ? 'selected' : '' }}>
                                    {{ $author->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('author_id')
                            <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset>
                <fieldset>
                    <div class="body-title">Loại trang <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="page_type" id="page_type" class="@error('page_type') is-invalid @enderror">
                            <option value="page" {{ old('page_type', $page->page_type) == 'page' ? 'selected' : '' }}>
                                Page</option>
                            <option value="blog_post"
                                {{ old('page_type', $page->page_type) == 'blog_post' ? 'selected' : '' }}>Blog Post
                            </option>
                        </select>
                        @error('page_type')
                            <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset>
                <fieldset>
                    <div class="body-title">Trạng thái <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="status" id="status" class="@error('status') is-invalid @enderror">
                            <option value="draft" {{ old('status', $page->status) == 'draft' ? 'selected' : '' }}>Nháp
                            </option>
                            <option value="published" {{ old('status', $page->status) == 'published' ? 'selected' : '' }}>
                                Đã xuất bản</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset>
                <fieldset>
                    <div class="body-title">Ảnh đại diện</div>
                    <div class="upload-image flex-grow d-block">
                        <div class="item up-load" style="display: flex; align-items: flex-start; gap: 24px;">
                            <label class="uploadfile h250" for="featured_image_url" style="flex:1;">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Kéo thả ảnh vào đây hoặc <span class="tf-color">nhấn để
                                        chọn</span></span>
                                <img id="featured_image_url-preview" src="#" alt=""
                                    style="display: none; max-width:120px;">
                                <input type="file" id="featured_image_url" name="featured_image_url"
                                    class="@error('featured_image_url') is-invalid @enderror" accept="image/*">
                            </label>
                        </div>
                        @error('featured_image_url')
                            <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset>

                {{-- Ảnh cũ --}}
                @if ($page->featured_image_url)
                    <div id="old-image-wrap" style="text-align:center;">
                        <div style="font-size:13px; color:#888;">Ảnh hiện tại</div>
                        <img id="old-image" src="{{ asset('storage/' . $page->featured_image_url) }}" alt="Ảnh đại diện"
                            style="max-width: 120px; margin-top:10px;">
                    </div>
                @endif

                <fieldset>
                    <div class="body-title">Meta title</div>
                    <input class="flex-grow form-control @error('meta_title') is-invalid @enderror" type="text"
                        placeholder="Meta title" name="meta_title" id="meta_title"
                        value="{{ old('meta_title', $page->meta_title) }}">
                    @error('meta_title')
                        <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                    @enderror
                </fieldset>
                <fieldset>
                    <div class="body-title">Meta description</div>
                    <textarea class="flex-grow @error('meta_description') is-invalid @enderror" name="meta_description"
                        id="meta_description" rows="2" placeholder="Meta description">{{ old('meta_description', $page->meta_description) }}</textarea>
                    @error('meta_description')
                        <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                    @enderror
                </fieldset>
                <fieldset>
                    <div class="body-title">Ngày xuất bản</div>
                    <input class="flex-grow form-control @error('published_at') is-invalid @enderror"
                        type="datetime-local" name="published_at" id="published_at"
                        value="{{ old('published_at', $page->published_at ? $page->published_at->format('Y-m-d\TH:i') : '') }}">
                    @error('published_at')
                        <div class="invalid-feedback fw-bold fs-5" style="display:block;">{{ $message }}</div>
                    @enderror
                </fieldset>
                <fieldset>
                    <div class="body-title">Ngày tạo</div>
                    <input type="text" class="form-control" value="{{ $page->created_at }}" readonly>
                </fieldset>
                <fieldset>
                    <div class="body-title">Ngày cập nhật</div>
                    <input type="text" class="form-control" value="{{ $page->updated_at }}" readonly>
                </fieldset>
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Cập nhật</button>
                    <a href="{{ route('admin.pages.index') }}" class="tf-button w208"
                        style="background-color: #6c757d;">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Tạo slug tự động khi nhập tiêu đề
        document.getElementById('title').addEventListener('input', function() {
            let title = this.value;
            let slug = title.toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9\s-]/g, '')
                .trim().replace(/\s+/g, '-');
            document.getElementById('slug').value = slug;
        });

        // Hiển thị ảnh xem trước khi tải lên
        document.getElementById('featured_image_url').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                const preview = document.getElementById('featured_image_url-preview');
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
                // Ẩn ảnh cũ nếu có
                const oldImg = document.getElementById('old-image');
                if (oldImg) oldImg.style.display = 'none';
            }
        });
    </script>
@endsection
