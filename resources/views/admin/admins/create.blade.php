@extends('layouts.vertical', ['title' => 'Create Admin', 'sub_title' => 'Admins'])

@section('css')
    @vite(['node_modules/choices.js/public/assets/styles/choices.min.css'])
@endsection

@section('content')
    <form action="{{ route('admin.admins.store') }}" method="POST" id="adminForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Left Side: Avatar & Quick Info -->
            <div class="col-xl-3 col-lg-4">
                <div class="card text-center">
                    <div class="card-header bg-light-subtle">
                        <h4 class="card-title">Admin Avatar</h4>
                    </div>
                    <div class="card-body">
                        <div class="position-relative d-inline-block">
                            <img id="avatar-preview" src="{{ asset('images/users/avatar-1.jpg') }}" alt="avatar" class="img-fluid rounded-circle border p-1 bg-light" style="width: 150px; height: 150px; object-fit: cover;">
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
                        <div class="form-check form-switch card-switch d-flex justify-content-between align-items-center ps-0">
                            <label class="form-check-label fw-medium" for="is_blocked">Active Account</label>
                            <input class="form-check-input" type="checkbox" name="active" id="is_blocked" checked>
                        </div>
                        <p class="text-muted fs-12 mt-2">Blocked admins cannot log into the dashboard.</p>
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
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter name" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="example@mail.com" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="role_id" class="form-label">Assign Role</label>
                                    <select class="form-control" id="role_id" name="role_id" data-choices data-choices-groups data-placeholder="Select Role" required>
                                        <option value="">Choose a role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-light-subtle">
                        <h4 class="card-title">Password Settings</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Min 8 characters" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Repeat password" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-light mb-3 rounded d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.admins.list') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Create Admin</button>
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

        // AJAX Form Submission
        const form = document.getElementById('adminForm');
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                if (result.success) {
                    Swal.fire('Success', 'Admin created successfully', 'success').then(() => {
                        window.location.href = result.redirect;
                    });
                } else {
                    Swal.fire('Error', result.msg || 'Validation failed', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Something went wrong', 'error');
            }
        });
    });
</script>
@endsection
