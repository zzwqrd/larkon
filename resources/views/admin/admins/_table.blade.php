<table class="table align-middle mb-0 table-hover table-centered">
    <thead class="bg-light-subtle">
        <tr>
            <th style="width: 20px;">
                <div class="form-check ms-1">
                    <input type="checkbox" class="form-check-input" id="checkAll">
                </div>
            </th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($admins as $admin)
            <tr>
                <td>
                    <div class="form-check ms-1">
                        @if($admin->role_id == 1)
                            <iconify-icon icon="solar:lock-bold-duotone" class="text-warning fs-18"
                                title="System Protected"></iconify-icon>
                        @else
                            <input type="checkbox" class="form-check-input product-checkbox" value="{{ $admin->id }}">
                        @endif
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded bg-light d-flex align-items-center justify-content-center"
                            style="height: 40px; width: 40px;">
                            <img src="{{ asset($admin->avatar ?? 'images/users/avatar-1.jpg') }}" alt=""
                                class="img-fluid rounded-circle">
                        </div>
                        <p class="text-dark fw-medium fs-14 mb-0">{{ $admin->name }}</p>
                    </div>
                </td>
                <td>{{ $admin->email }}</td>
                <td>
                    <span class="badge bg-soft-info text-info">{{ $admin->role->name ?? 'No Role' }}</span>
                </td>
                <td>
                    @if($admin->is_blocked)
                        <span class="badge bg-danger">Blocked</span>
                    @else
                        <span class="badge bg-success">Active</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-2">


                        <x-ui.edit-button :route="route('admins.edit', $admin->id)" />
                        @if($admin->role_id != 1)
                            <x-ui.delete-button :id="$admin->id" :route="route('admins.destroy', $admin->id)" />
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <x-ui.empty-table :cols="7" />
        @endforelse
    </tbody>
</table>

<x-ui.table-pagination :items="$admins" />