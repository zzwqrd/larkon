@extends('layouts.vertical', ['title' => __('messages.edit') . ' ' . __('messages.roles'), 'sub_title' => __('messages.roles')])

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" id="roleForm">
            @csrf
            @method('PUT')
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="card-title mb-1">{{ __('messages.edit') }} {{ __('messages.roles') }}: {{ $role->name }}</h4>
                            <p class="text-muted fs-13 mb-3">{{ __('messages.edit_role_subtitle') }}</p>
                            <div class="mb-0">
                                <label for="roleName" class="form-label fw-bold">{{ __('messages.roles') }}</label>
                                <input type="text" name="name" id="roleName" class="form-control form-control-lg" value="{{ $role->name }}" placeholder="e.g. Senior Manager" required>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-light px-4 me-2">{{ __('messages.cancel') }}</a>
                            <button type="submit" class="btn btn-primary px-4 bg-gradient">
                                <iconify-icon icon="solar:diskette-bold-duotone" class="align-middle me-1 fs-18"></iconify-icon> {{ __('messages.edit') }} {{ __('messages.roles') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $allPermsChecked = true;
            @endphp

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
                    @php
                        $groupRouteNames = array_column($routes, 'name');
                        $isGroupAllChecked = count(array_intersect($groupRouteNames, $rolePermissions)) === count($groupRouteNames);
                        if (!$isGroupAllChecked) $allPermsChecked = false;
                    @endphp
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden permission-card">
                            <div class="card-header bg-light-subtle d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                        <iconify-icon icon="solar:folder-with-files-bold-duotone" class="fs-16 text-primary"></iconify-icon>
                                    </div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $group }}</h6>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input check-all-group" type="checkbox" id="check_{{ md5($group) }}" {{ $isGroupAllChecked ? 'checked' : '' }}>
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
                                                       value="{{ $route['name'] }}" id="perm_{{ str_replace('.', '_', $route['name']) }}"
                                                       {{ in_array($route['name'], $rolePermissions) ? 'checked' : '' }}>
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

{{-- Pass the initial global state to JS --}}
<input type="hidden" id="initialGlobalState" value="{{ $allPermsChecked ? '1' : '0' }}">
@endsection

@section('script-bottom')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const globalToggle = document.getElementById('globalCheckAll');
        const initialGlobalState = document.getElementById('initialGlobalState').value;
        
        // Finalize initial state
        if (initialGlobalState === '1') globalToggle.checked = true;

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

        // 1. Global Select All Click
        globalToggle.addEventListener('change', function() {
            document.querySelectorAll('.perm-checkbox, .check-all-group').forEach(cb => {
                cb.checked = this.checked;
            });
        });

        // 2. Group Select All Click
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
                if (result.success) {
                    Swal.fire({
                        title: 'Updated!',
                        text: 'Role updated successfully',
                        icon: 'success',
                        customClass: { confirmButton: 'btn btn-primary' }
                    }).then(() => {
                        window.location.href = result.redirect;
                    });
                } else {
                    Swal.fire('Error', result.msg || 'Failed to update role', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'An unexpected error occurred', 'error');
            }
        });
    });
</script>
@endsection
