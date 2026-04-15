@extends('layouts.vertical', ['title' => __('messages.create_role'), 'sub_title' => __('messages.roles')])

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('roles.store') }}" method="POST" id="roleForm">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="card-title mb-1">{{ __('messages.create_role') }}</h4>
                            <p class="text-muted fs-13 mb-3">{{ __('messages.create_role_subtitle') }}</p>
                            <div class="mb-0">
                                <label for="roleName" class="form-label fw-bold">{{ __('messages.roles') }}</label>
                                <input type="text" name="name" id="roleName" class="form-control form-control-lg" placeholder="e.g. Senior Manager" required>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('roles.index') }}" class="btn btn-light px-4 me-2">{{ __('messages.cancel') }}</a>
                            <button type="submit" class="btn btn-primary px-4 bg-gradient">
                                <iconify-icon icon="solar:diskette-bold-duotone" class="align-middle me-1 fs-18"></iconify-icon> {{ __('messages.add') }} {{ __('messages.roles') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="text-uppercase fw-bold text-muted mb-0">
                    <iconify-icon icon="solar:shield-keyhole-bold-duotone" class="align-middle me-1 text-primary"></iconify-icon> {{ __('messages.comprehensive_permissions') }}
                </h5>
                <div class="form-check form-switch px-0">
                    <label class="form-check-label fw-bold me-2" for="globalCheckAll">{{ __('messages.select_all_permissions') }}</label>
                    <input class="form-check-input" type="checkbox" id="globalCheckAll">
                </div>
            </div>

            <div class="row">
                @foreach ($permissions as $group => $routes)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden permission-card">
                            <div class="card-header bg-light-subtle d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                        <iconify-icon icon="solar:folder-with-files-bold-duotone" class="fs-16 text-primary"></iconify-icon>
                                    </div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ __('messages.' . strtolower($group)) == 'messages.' . strtolower($group) ? $group : __('messages.' . strtolower($group)) }}</h6>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input check-all-group" type="checkbox" id="check_{{ $group }}">
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @foreach ($routes as $route)
                                        <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3 border-light">
                                            <div class="d-flex align-items-center">
                                                <label class="form-check-label fs-13 text-dark mb-0" for="perm_{{ str_replace('.', '_', $route['name']) }}">
                                                    {{ $route['title'] }}
                                                </label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" 
                                                       value="{{ $route['name'] }}" id="perm_{{ str_replace('.', '_', $route['name']) }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
    </div>
</div>
@endsection

@section('script-bottom')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const globalToggle = document.getElementById('globalCheckAll');

        // Function to update Group and Global switches based on checkboxes
        function updateSwitches() {
            let allGroupsChecked = true;

            document.querySelectorAll('.permission-card').forEach(card => {
                const groupToggle = card.querySelector('.check-all-group');
                const checkboxes = card.querySelectorAll('.perm-checkbox');
                const allCheckedInGroup = Array.from(checkboxes).every(cb => cb.checked);
                
                groupToggle.checked = allCheckedInGroup;
                if (!allCheckedInGroup) allGroupsChecked = false;
            });

            globalToggle.checked = allGroupsChecked;
        }

        // 1. Global Select All
        globalToggle.addEventListener('change', function() {
            document.querySelectorAll('.perm-checkbox, .check-all-group').forEach(cb => {
                cb.checked = this.checked;
            });
        });

        // 2. Group Select All
        document.querySelectorAll('.check-all-group').forEach(groupToggle => {
            groupToggle.addEventListener('change', function () {
                const card = this.closest('.permission-card');
                const checkboxes = card.querySelectorAll('.perm-checkbox');
                checkboxes.forEach(cb => cb.checked = this.checked);
                
                // Update global switch after group change
                const allGroupsChecked = Array.from(document.querySelectorAll('.check-all-group')).every(gt => gt.checked);
                globalToggle.checked = allGroupsChecked;
            });
        });

        // 3. Single Permission Click
        document.querySelectorAll('.perm-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSwitches();
            });
        });

        // AJAX Form Submission
        const form = document.getElementById('roleForm');
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
                if (response.ok) {
                    if (result.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Role created successfully',
                            icon: 'success',
                            customClass: { confirmButton: 'btn btn-primary' }
                        }).then(() => {
                            window.location.href = result.redirect;
                        });
                    } else {
                        Swal.fire('Error', result.msg || 'Failed to save role', 'error');
                    }
                } else if (response.status === 422) {
                    // Handle Laravel Validation Errors
                    let errorMessages = '';
                    if (result.errors) {
                        for (const field in result.errors) {
                            errorMessages += result.errors[field][0] + '<br>';
                        }
                    } else if (result.message) {
                        errorMessages = result.message;
                    }
                    Swal.fire('Validation Error', errorMessages || 'Please check your inputs.', 'warning');
                } else {
                    Swal.fire('Error', result.msg || result.message || 'Failed to save role', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'An unexpected error occurred', 'error');
            }
        });
    });
</script>
@endsection
