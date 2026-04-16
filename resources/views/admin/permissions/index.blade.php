@extends('layouts.vertical', ['title' => __('messages.permissions'), 'sub_title' => __('messages.general')])

@section('content')
    <div class="row">
        <div class="col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 d-flex align-items-center gap-2">{{ __('messages.total_admins') }}
                            </h4>
                            <p class="text-dark fw-bold fs-24 mb-0">{{ \App\Models\Admin::count() }}</p>
                        </div>
                        <div>
                            <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"
                                    class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 d-flex align-items-center gap-2">{{ __('messages.active_roles') }}
                            </h4>
                            <p class="text-dark fw-bold fs-24 mb-0">{{ \App\Models\Role::count() }}</p>
                        </div>
                        <div>
                            <div class="avatar-md bg-info bg-opacity-10 rounded">
                                <iconify-icon icon="solar:shield-star-bold-duotone"
                                    class="fs-32 text-info avatar-title"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 d-flex align-items-center gap-2">{{ __('messages.system_routes') }}
                            </h4>
                            <p class="text-dark fw-bold fs-24 mb-0">{{ count($permissions) }}</p>
                        </div>
                        <div>
                            <div class="avatar-md bg-success bg-opacity-10 rounded">
                                <iconify-icon icon="solar:routing-bold-duotone"
                                    class="fs-32 text-success avatar-title"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-2 d-flex align-items-center gap-2">{{ __('messages.security_shield') }}
                            </h4>
                            <p class="text-dark fw-bold fs-24 mb-0">{{ __('messages.active') }}</p>
                        </div>
                        <div>
                            <div class="avatar-md bg-warning bg-opacity-10 rounded">
                                <iconify-icon icon="solar:shield-check-bold-duotone"
                                    class="fs-32 text-warning avatar-title"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <x-ui.table-card :title="__('messages.comprehensive_permissions')" :indexRoute="route('admin.permissions.index')">
                <div id="table_content_append">
                    @include('admin.permissions._table', ['permissions' => $permissions])
                </div>
            </x-ui.table-card>
        </div>
    </div>

    <!-- Manage Permission Roles Modal -->
    <div class="modal fade" id="managePermissionModal" tabindex="-1" aria-labelledby="managePermissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title d-flex align-items-center gap-2" id="managePermissionModalLabel">
                        <iconify-icon icon="solar:settings-bold-duotone"></iconify-icon>
                        {{ __('messages.manage_roles') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updatePermissionRolesForm">
                    @csrf
                    <input type="hidden" name="permission" id="modal_permission_name">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase mb-1">{{ __('messages.permission') }}</label>
                            <h6 id="modal_permission_title" class="text-primary mb-0"></h6>
                            <code id="modal_permission_code" class="fs-11"></code>
                        </div>
                        <hr class="my-3 opacity-50">
                        <label class="form-label fw-bold mb-2">{{ __('messages.assign_to_roles') }}</label>
                        <div class="row g-2" id="roles_list">
                            @foreach($allRoles as $role)
                                <div class="col-md-6">
                                    <div class="form-check card-radio p-2 border rounded hover-shadow-sm">
                                        <input class="form-check-input role-checkbox" type="checkbox" name="roles[]" 
                                               value="{{ $role->id }}" id="role_{{ $role->id }}" 
                                               {{ $role->id == 1 ? 'disabled checked' : '' }}>
                                        <label class="form-check-label d-flex align-items-center gap-2" for="role_{{ $role->id }}">
                                            <iconify-icon icon="solar:user-speak-rounded-bold-duotone" class="fs-18 text-muted"></iconify-icon>
                                            <span class="fs-13 fw-medium">{{ $role->name }}</span>
                                            @if($role->id == 1)
                                                <span class="badge bg-danger-subtle text-danger ms-auto fs-10">System</span>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer bg-light-subtle">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:diskette-bold-duotone" class="fs-18"></iconify-icon>
                            {{ __('messages.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script-bottom')
    <script src="{{ asset('js/dynamic-table.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get Modal Instance
            const manageModalEl = document.getElementById('managePermissionModal');
            const manageModal = new bootstrap.Modal(manageModalEl);
            const updateForm = document.getElementById('updatePermissionRolesForm');
            const submitBtn = updateForm.querySelector('button[type="submit"]');

            // Handle Manage Roles Click (using event delegation for dynamic content)
            document.addEventListener('click', function(e) {
                const button = e.target.closest('.manage-permission-roles');
                if (button) {
                    const permission = button.getAttribute('data-permission');
                    const title = button.getAttribute('data-title');
                    const assignedRoles = JSON.parse(button.getAttribute('data-assigned') || '[]');

                    document.getElementById('modal_permission_name').value = permission;
                    document.getElementById('modal_permission_title').textContent = title;
                    document.getElementById('modal_permission_code').textContent = permission;

                    // Reset checkboxes
                    updateForm.querySelectorAll('.role-checkbox').forEach(checkbox => {
                        const label = checkbox.closest('.form-check').querySelector('label span.fs-13');
                        const roleName = label ? label.textContent.trim() : '';

                        if (checkbox.value === "1") {
                            checkbox.checked = true; // Super Admin always checked
                        } else {
                            checkbox.checked = assignedRoles.includes(roleName);
                        }
                    });

                    manageModal.show();
                }
            });

            // Handle Form Submission
            updateForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const originalHtml = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

                const formData = new FormData(updateForm);

                fetch("{{ route('admin.permissions.update-roles') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        manageModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Refresh table (Check if dynamic-table.js has a refresh mechanism)
                        if (typeof window.loadTableData === 'function') {
                            window.loadTableData();
                        } else {
                            setTimeout(() => location.reload(), 1500);
                        }
                    } else {
                        throw new Error(data.message || 'Something went wrong');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Something went wrong. Please try again.'
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHtml;
                });
            });
        });
    </script>
@endsection