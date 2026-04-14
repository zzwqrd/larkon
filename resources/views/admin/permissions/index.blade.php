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
            <x-ui.table-card :title="__('messages.comprehensive_permissions')" :indexRoute="route('permissions.index')">
                <div id="table_content_append">
                    @include('admin.permissions._table', ['permissions' => $permissions])
                </div>
            </x-ui.table-card>
        </div>
    </div>
@endsection

@section('script-bottom')
    <script src="{{ asset('js/dynamic-table.js') }}"></script>
@endsection