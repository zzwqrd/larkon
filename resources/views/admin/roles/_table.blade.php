<table class="table align-middle mb-0 table-hover table-centered">
    <thead class="bg-light-subtle">
        <tr>
            <th style="width: 20px;">
                <div class="form-check ms-1">
                    <input type="checkbox" class="form-check-input" id="checkAll">
                </div>
            </th>
            <th>Role Name</th>
            <th>Admins Count</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($roles as $role)
            <tr>
                <td>
                    <div class="form-check ms-1" {{ $role->id == 1 ? 'style=display:none' : '' }}>
                        <input type="checkbox" class="form-check-input product-checkbox" value="{{ $role->id }}">
                    </div>
                </td>
                <td>
                    <p class="text-dark fw-medium fs-14 mb-0">{{ $role->name }}</p>
                </td>
                <td>
                    <span
                        class="badge bg-soft-primary text-primary">{{ $role->admins_count ?? $role->admins->count() }}</span>
                </td>
                <td>{{ $role->created_at->format('Y-m-d') }}</td>
                <td>
                    <div class="d-flex gap-2">
                        <x-ui.edit-button :route="route('roles.index', ['edit' => $role->id])" />
                        @if ($role->id != 1)
                            <x-ui.delete-button :id="$role->id" :route="route('roles.destroy', $role->id)" />
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No roles found</td>
            </tr>
        @endforelse
    </tbody>
</table>

<x-ui.table-pagination :items="$roles" />