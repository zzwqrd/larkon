@extends('layouts.vertical', ['title' => 'Edit Admin', 'sub_title' => 'Admins'])

@section('css')
    @vite(['node_modules/choices.js/public/assets/styles/choices.min.css'])
@endsection

@section('content')
    <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" id="adminForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Left Side: Avatar & Status -->
            <div class="col-xl-3 col-lg-4">
                <div class="card text-center">
                    <div class="card-header bg-light-subtle">
                        <h4 class="card-title">Admin Avatar</h4>
                    </div>
                    <div class="card-body">
                        <div class="position-relative d-inline-block">
                            <img id="avatar-preview" src="{{ $admin->avatar ? asset($admin->avatar) : asset('images/users/avatar-1.jpg') }}" alt="avatar" class="img-fluid rounded-circle border p-1 bg-light" style="width: 150px; height: 150px; object-fit: cover;">
                            <label for="avatar-input" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 cursor-pointer shadow-sm" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                <i class="bx bx-camera"></i>
                                <input type="file" name="avatar" id="avatar-input" class="d-none" accept="image/*">
                            </label>
                        </div>
                        <p class="text-muted mt-3 mb-0 fs-13">Allowed: JPG, PNG. Max 2MB</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-light-subtle">
                        <h4 class="card-title">Account Status</h4>
                    </div>
                    <div class="card-body">
                        @php $isSuper = ($admin->id == 1 || $admin->email == 'admin@admin.com'); @endphp
                        <div class="form-check form-switch card-switch d-flex justify-content-between align-items-center ps-0">
                            <label class="form-check-label fw-medium" for="is_blocked">Active Account</label>
                            <input class="form-check-input" type="checkbox" name="active" id="is_blocked" {{ !$admin->is_blocked ? 'checked' : '' }} {{ $isSuper ? 'disabled' : '' }}>
                        </div>
                        @if($isSuper)
                            <input type="hidden" name="active" value="on">
                            <p class="text-danger fs-12 mt-2">Super Admin account must always be active.</p>
                        @else
                            <p class="text-muted fs-12 mt-2">Blocked admins cannot log into the dashboard.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Side: Main Form -->
            <div class="col-xl-9 col-lg-8">
                <div class="card">
                    <div class="card-header bg-light-subtle">
                        <h4 class="card-title">Basic Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter name" value="{{ $admin->name }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="example@mail.com" value="{{ $admin->email }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="role_id" class="form-label">Assign Role</label>
                                    @php $isSuper = ($admin->id == 1 || $admin->email == 'admin@admin.com'); @endphp
                                    <select class="form-control" id="role_id" name="role_id" data-choices data-choices-groups data-placeholder="Select Role" required {{ $isSuper ? 'disabled' : '' }}>
                                        <option value="">Choose a role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $admin->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @if($isSuper)
                                        <input type="hidden" name="role_id" value="{{ $admin->role_id }}">
                                        <div class="form-text text-danger fs-12">Super Admin role cannot be changed.</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-primary border-opacity-25 bg-primary bg-opacity-10 shadow-none">
                    <div class="card-header bg-transparent">
                        <h4 class="card-title">Change Password</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label text-dark">New Password <span class="text-muted fs-12 fw-normal">(Leave blank to keep current)</span></label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label text-dark">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Repeat new password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-light mb-3 rounded d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.admins.list') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Update Admin</button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script-bottom')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Avatar Preview logic
        const avatarInput = document.getElementById('avatar-input');
        const avatarPreview = document.getElementById('avatar-preview');

        avatarInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    avatarPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // AJAX Form Submission (PUT Method handling)
        const form = document.getElementById('adminForm');
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('_method', 'PUT'); // Explicitly set for Laravel
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST', // POST with _method spoofing for PUT
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                if (result.success) {
                    Swal.fire('Updated', 'Admin updated successfully', 'success').then(() => {
                        window.location.href = result.redirect;
                    });
                } else {
                    Swal.fire('Error', result.msg || 'Update failed', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Something went wrong', 'error');
            }
        });
    });
</script>
@endsection
