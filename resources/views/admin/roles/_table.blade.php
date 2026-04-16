<div class="table-responsive">
    <table class="table align-middle mb-0 table-hover table-centered">
        <thead class="bg-light-subtle">
            <tr>
                <th style="width: 20px;">
                    <div class="form-check ms-1">
                        <input type="checkbox" class="form-check-input" id="checkAll">
                    </div>
                </th>
                <th>{{ __('messages.roles') }}</th>
                <th>{{ __('messages.users') }}</th>
                <th>{{ __('messages.permissions') }}</th>
                <th>{{ __('messages.status') }}</th>
                <th>{{ __('messages.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
                <tr>
                    <td>
                        <div class="form-check ms-1">
                            @if($role->id == 1)
                                <iconify-icon icon="solar:lock-bold-duotone" class="text-warning fs-18"
                                    title="System Protected"></iconify-icon>
                            @else
                                <input type="checkbox" class="form-check-input product-checkbox" value="{{ $role->id }}">
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div
                                class="avatar-sm bg-primary-subtle rounded d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:shield-user-bold" class="fs-20 text-primary"></iconify-icon>
                            </div>
                            <div>
                                <h5 class="mb-0 fs-14">{{ $role->name }}</h5>
                                <span class="text-muted fs-12">ID: #{{ $role->id }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="avatar-group">
                            @foreach($role->admins->take(3) as $admin)
                                <div class="avatar">
                                    <img src="{{ asset($admin->avatar ?? 'images/users/avatar-1.jpg') }}" alt=""
                                        class="rounded-circle avatar-xs">
                                </div>
                            @endforeach
                            @if($role->admins_count > 3)
                                <div class="avatar">
                                    <span
                                        class="avatar-xs d-flex align-items-center justify-content-center bg-light text-muted rounded-circle fw-bold shadow">
                                        +{{ $role->admins_count - 3 }}
                                    </span>
                                </div>
                            @elseif($role->admins_count == 0)
                                <span class="text-muted fs-12 italic">{{ __('messages.unassigned') }}</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-info-subtle text-info border py-1 px-2">
                            {{ $role->permissions_count ?? $role->permissions()->count() }} {{ __('messages.permissions') }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success-subtle text-success py-1 px-2">{{ __('messages.active') }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <x-ui.edit-button :route="route('admin.roles.edit', $role->id)" />
                            @if($role->id != 1)
                                <x-ui.delete-button :id="$role->id" :route="route('admin.roles.destroy', $role->id)" />
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <x-ui.empty-table :cols="6" />
            @endforelse
        </tbody>
    </table>
</div>

<x-ui.table-pagination :items="$roles" />