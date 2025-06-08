{{-- filepath: c:\laragon\www\Funori-main\resources\views\admin\users\edit.blade.php --}}
@extends('admin.layout.admin')

@section('title', 'Sửa user')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Edit User</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.users.index') }}">
                            <div class="text-tiny">User List</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit User</div>
                    </li>
                </ul>
            </div>
            <!-- edit-user -->
            <form class="form-add-new-user form-style-2" enctype="multipart/form-data" method="POST"
                action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="wg-box">
                    <div class="left">
                        <h5 class="mb-4">Account</h5>
                        <div class="body-text1">Bạn chỉ có thể chỉnh sửa quyền và trạng thái tài khoản</div>
                        <div class="avatar-upload mt-3 mb-4">
                            <label for="avatarInput">
                                <img src="{{ asset($user->avatar_url ? $user->avatar_url : 'images/images.jpg') }}"
                                    alt="Avatar" class="avatar-preview" id="avatarPreview">
                            </label>
                        </div>
                    </div>
                    <div class="right flex-grow">
                        <fieldset class="name mb-24">
                            <div class="body-title mb-10">Name</div>
                            <input class="flex-grow" type="text" name="full_name" value="{{ $user->full_name }}"
                                readonly>
                        </fieldset>
                        <fieldset class="email mb-24">
                            <div class="body-title mb-10">Email</div>
                            <input class="flex-grow" type="email" name="email" value="{{ $user->email }}" readonly>
                        </fieldset>
                        <fieldset class="phone mb-24">
                            <div class="body-title mb-10">Phone Number</div>
                            <input class="flex-grow" type="text" name="phone_number" value="{{ $user->phone_number }}"
                                readonly>
                        </fieldset>
                        <fieldset class="password mb-24">
                            <div class="body-title mb-10">Password</div>
                            <div class="password-input-wrapper">
                                <input class="password-input" type="password" placeholder="Password" name="password"
                                    value="*********" readonly id="passwordInput" style="padding-right: 36px;">

                                <span id="resetPasswordIcon" title="Reset password" style="font-size: 18px">
                                    <i class="fas fa-sync-alt"></i>
                                </span>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="wg-box">
                    <div class="left">
                        <h5 class="mb-4">Permission</h5>
                    </div>
                    <div class="right flex-grow">
                        <fieldset class="mb-24">
                            <div class="body-title mb-10">Account Status</div>
                            <div class="radio-buttons">
                                <div class="item">
                                    <input type="radio" name="account_status" id="apply-product1" value="active"
                                        {{ old('account_status', $user->account_status) == 'active' ? 'checked' : '' }}>
                                    <label for="apply-product1"><span class="body-title-2">Active</span></label>
                                </div>
                                <div class="item">
                                    <input type="radio" name="account_status" id="apply-product2" value="inactive"
                                        {{ old('account_status', $user->account_status) == 'inactive' ? 'checked' : '' }}>
                                    <label for="apply-product2"><span class="body-title-2">Inactive</span></label>
                                </div>
                                <div class="item">
                                    <input type="radio" name="account_status" id="apply-product3" value="banned"
                                        {{ old('account_status', $user->account_status) == 'banned' ? 'checked' : '' }}>
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
                                        {{ old('role', $user->role) == 'admin' ? 'checked' : '' }}>
                                    <label for="create-product1"><span class="body-title-2">Admin</span></label>
                                </div>
                                <div class="item">
                                    <input type="radio" name="role" id="create-product2" value="user"
                                        {{ old('role', $user->role) == 'user' ? 'checked' : '' }}>
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
            <!-- /edit-user -->
            <!-- Modal đặt lại mật khẩu -->
            <div id="resetPasswordModal"
                style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3); align-items:center; justify-content:center;">
                <div
                    style="background:#fff; padding:48px 36px 36px 36px; border-radius:18px; min-width:420px; max-width:98vw; box-shadow:0 12px 48px rgba(0,0,0,0.18); position:relative;">
                    <span id="closeResetModal"
                        style="position:absolute; top:12px; right:16px; font-size:28px; cursor:pointer; line-height:1; background:none; border:none; font-family:sans-serif;">
                        &times;
                    </span>



                    <h3 style="margin-bottom:28px; font-size:2.5rem;">Reset Password</h3>
                    <form id="resetPasswordForm" method="POST"
                        action="{{ route('admin.users.resetPassword', $user->id) }}">
                        @csrf
                        <div style="margin-bottom:20px;">
                            <label style="font-weight:700; font-size: 10px;">Old Password</label>
                            <input type="password" name="old_password" class="form-control strong-input" required
                                style="width:100%;margin-top:8px; background-color: #e5e2e2;">
                        </div>
                        <div style="margin-bottom:20px;">
                            <label style="font-weight:700; font-size: 10px;">New Password</label>
                            <input type="password" name="new_password" class="form-control strong-input" required
                                style="width:100%;margin-top:8px; background-color: #e5e2e2;">
                        </div>
                        <div style="margin-bottom:28px;">
                            <label style="font-weight:600; font-size: 10px;">Confirm Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control strong-input"
                                required style="width:100%;margin-top:8px; background-color: #e5e2e2;">
                        </div>
                        <button type="submit" class="tf-button w100" style="font-size:1.1rem;">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ...avatar preview code...

            // Hiện modal khi nhấn icon reset
            var resetIcon = document.getElementById('resetPasswordIcon');
            var modal = document.getElementById('resetPasswordModal');
            var closeBtn = document.getElementById('closeResetModal');
            if (resetIcon && modal && closeBtn) {
                resetIcon.addEventListener('click', function(e) {
                    e.preventDefault();
                    modal.style.display = 'flex';
                });
                closeBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                });
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) modal.style.display = 'none';
                });
            }
        });
    </script>
@endpush
