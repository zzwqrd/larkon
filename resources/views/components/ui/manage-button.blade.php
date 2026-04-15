@props([
    'permission' => '',
    'title' => '',
    'assigned' => []
])

<button type="button" class="btn btn-soft-warning btn-sm manage-permission-roles" data-permission="{{ $permission }}"
    data-title="{{ $title }}" data-assigned="{{ is_array($assigned) ? json_encode($assigned) : $assigned }}"
    title="{{ __('messages.manage_roles') }}">
    <iconify-icon icon="solar:settings-outline" class="align-middle fs-18"></iconify-icon>
</button>
