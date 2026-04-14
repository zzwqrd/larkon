<div class="table-responsive">
    <table class="table align-middle mb-0 table-hover table-centered">
        <thead class="bg-light-subtle">
            <tr>
                <th style="width: 20px;">#</th>
                <th>{{ __('messages.permission_name') }}</th>
                <th>{{ __('messages.assigned_to_roles') }}</th>
                <th>{{ __('messages.category') }}</th>
                <th>{{ __('messages.status') }}</th>
                <th>{{ __('messages.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permissions as $index => $perm)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-xs bg-light rounded d-flex align-items-center justify-content-center">
                                <iconify-icon icon="solar:key-minimalistic-bold-duotone" class="text-muted"></iconify-icon>
                            </div>
                            <div>
                                <h6 class="mb-0 fs-14">{{ $perm['title'] }}</h6>
                                <code class="fs-11 text-primary">{{ $perm['name'] }}</code>
                            </div>
                        </div>
                    </td>
                    <td>
                        @forelse($perm['assigned_to'] as $roleName)
                            <span class="badge bg-primary-subtle text-primary py-1 px-2 fs-11 me-1">{{ $roleName }}</span>
                        @empty
                            <span class="text-muted fs-11 italic">{{ __('messages.unassigned') }}</span>
                        @endforelse
                    </td>
                    <td>
                        <span
                            class="badge bg-light text-dark border py-1 px-2 fs-11">{{ ucfirst($perm['category'] ?? 'General') }}</span>
                    </td>
                    <td>
                        <span
                            class="badge bg-success-subtle text-success py-1 px-2 fs-11">{{ __('messages.active') }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="#!" class="btn btn-light btn-sm" title="Security Audit"><iconify-icon
                                    icon="solar:shield-warning-bold" class="align-middle fs-16"></iconify-icon></a>
                        </div>
                    </td>
                </tr>
            @empty
                <x-ui.empty-table :cols="6" />
            @endforelse
        </tbody>
    </table>
</div>

<x-ui.table-pagination :items="$permissions" />