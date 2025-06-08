@extends('admin.layout.admin')

@section('title', 'Thêm user')

@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Add New User</h3>
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
                            <div class="text-tiny">User</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Add New User</div>
                    </li>
                </ul>
            </div>
            <!-- add-new-user -->
            <form class="form-add-new-user form-style-2" enctype="multipart/form-data" method="POST"
                action="{{ route('admin.users.store') }}">
                @csrf
                <div class="wg-box">
                    <div class="left">
                        <h5 class="mb-4">Account</h5>
                        <div class="body-text1">Fill in the information below to add a new account</div>
                        <div class="avatar-upload mt-3 mb-4">
                            <label for="avatarInput">
                                <img src="{{ asset('images/images.jpg') }}" alt="Avatar" class="avatar-preview"
                                    id="avatarPreview">
                            </label>
                            <input type="file" id="avatarInput" name="avatar_url" accept="image/*"
                                style="display: none;">
                            @error('avatar_url')
                                <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="right flex-grow">
                        <fieldset class="name mb-24">
                            <div class="body-title mb-10">Name</div>
                            <input class="flex-grow" type="text" placeholder="Username" name="full_name" tabindex="0"
                                value="" aria-required="true" value="{{ old('full_name') }}">
                            @error('full_name')
                                <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </fieldset>
                        <fieldset class="email mb-24">
                            <div class="body-title mb-10">Email</div>
                            <input class="flex-grow" type="email" placeholder="Email" name="email" tabindex="0"
                                value="" aria-required="true" value="{{ old('email') }}">
                            @error('email')
                                <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </fieldset>
                        <fieldset class="phone mb-24">
                            <div class="body-title mb-10">Phone Number</div>
                            <input class="flex-grow" type="text" placeholder="Phone Number" name="phone_number"
                                tabindex="0" value="{{ old('phone_number') }}" aria-required="true">
                            @error('phone_number')
                                <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </fieldset>
                        <fieldset class="password mb-24">
                            <div class="body-title mb-10">Password</div>
                            <input class="password-input" type="password" placeholder="Enter password" name="password"
                                tabindex="0" aria-required="true">
                            <span class="show-pass">
                                <i class="icon-eye view"></i>
                                <i class="icon-eye-off hide"></i>
                            </span>
                            @error('password')
                                <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </fieldset>
                        <fieldset class="password">
                            <div class="body-title mb-10">Confirm password</div>
                            <input class="password-input" type="password" placeholder="Confirm password"
                                name="password_confirmation" tabindex="0" aria-required="true">
                            <span class="show-pass">
                                <i class="icon-eye view"></i>
                                <i class="icon-eye-off hide"></i>
                            </span>
                            @error('password_confirmation')
                                <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>
                </div>
                <div class="wg-box">
                    <div class="left">
                        <h5 class="mb-4">Permission</h5>
                        <div class="body-text">Items that the account is allowed to edit</div>
                    </div>
                    <div class="right flex-grow">
                        <fieldset class="mb-24">
                            <div class="body-title mb-10">Account Status</div>
                            <div class="radio-buttons">
                                <div class="item">
                                    <input type="radio" name="account_status" id="apply-product1" value="active"
                                        {{ old('account_status') == 'active' ? 'checked' : '' }}>
                                    <label for="apply-product1"><span class="body-title-2">Active</span></label>
                                </div>
                                <div class="item">
                                    <input type="radio" name="account_status" id="apply-product2" value="inactive"
                                        {{ old('account_status', 'inactive') == 'inactive' ? 'checked' : '' }}>
                                    <label for="apply-product2"><span class="body-title-2">Inactive</span></label>
                                </div>
                                <div class="item">
                                    <input type="radio" name="account_status" id="apply-product3" value="banned"
                                        {{ old('account_status') == 'banned' ? 'checked' : '' }}>
                                    <label for="apply-product3"><span class="body-title-2">Banned</span></label>
                                </div>
                            </div>
                            @error('account_status')
                                <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </fieldset>
                        <fieldset>
                            <div class="body-title mb-10">Role</div>
                            <div class="radio-buttons">
                                <div class="item">
                                    <input type="radio" name="role" id="create-product1" value="admin"
                                        {{ old('role') == 'admin' ? 'checked' : '' }}>
                                    <label for="create-product1"><span class="body-title-2">Admin</span></label>
                                </div>
                                <div class="item">
                                    <input type="radio" name="role" id="create-product2" value="user"
                                        {{ old('role', 'user') == 'user' ? 'checked' : '' }}>
                                    <label for="create-product2"><span class="body-title-2">User</span></label>
                                </div>
                            </div>
                            @error('role')
                                <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>
                </div>
                <div class="bot">
                    <button class="tf-button w180" type="submit">Save</button>
                </div>
            </form>
            <!-- /add-new-user -->
        </div>
        <!-- /main-content-wrap -->
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var input = document.getElementById('avatarInput');
            var preview = document.getElementById('avatarPreview');
            if (input && preview) {
                input.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endpush
