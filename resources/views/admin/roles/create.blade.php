@extends('layouts.vertical', ['title' => 'Create Role', 'sub_title' => 'Roles'])

@section('content')
    <form action="{{ route('roles.store') }}" method="POST" id="roleForm">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <label for="roleName" class="form-label fw-bold">Role Name</label>
                                <input type="text" name="name" id="roleName" class="form-control" placeholder="Enter role name (e.g. Manager)" required>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bx bx-save me-1"></i> Save Role
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="mb-3 text-uppercase fw-bold text-muted mt-2">Permissions Matrix</h5>

        <div class="row">
            @foreach ($permissions as $group => $routes)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2">
                            <h6 class="mb-0 text-white fw-semibold">{{ strtoupper($group) }}</h6>
                            <div class="form-check form-switch mb-0">
                                <input class="form-switch-input check-all-group" type="checkbox" id="check_{{ $group }}">
                                <label class="form-check-label text-white fs-12 ms-1" for="check_{{ $group }}">Select All</label>
                            </div>
                        </div>
                        <div class="card-body py-2">
                            @foreach ($routes as $route)
                                <div class="form-check form-switch py-2 border-bottom border-light last-child-border-0">
                                    <input class="form-switch-input perm-checkbox" type="checkbox" name="permissions[]" 
                                           value="{{ $route['name'] }}" id="perm_{{ str_replace('.', '_', $route['name']) }}">
                                    <label class="form-check-label ms-2 text-dark" for="perm_{{ str_replace('.', '_', $route['name']) }}">
                                        {{ $route['title'] }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </form>
@endsection

@section('script-bottom')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Select All in Group logic
        document.querySelectorAll('.check-all-group').forEach(groupToggle => {
            groupToggle.addEventListener('change', function () {
                const card = this.closest('.card');
                const checkboxes = card.querySelectorAll('.perm-checkbox');
                checkboxes.forEach(cb => cb.checked = this.checked);
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
                    Swal.fire('Success', 'Role created successfully', 'success').then(() => {
                        window.location.href = result.redirect;
                    });
                }
            } catch (error) {
                Swal.fire('Error', 'Something went wrong', 'error');
            }
        });
    });
</script>
@endsection
