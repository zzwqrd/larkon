@extends('layouts.vertical', ['title' => 'Roles & Permissions', 'sub_title' => 'General'])

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <x-ui.table-card title="Management Roles" :addRoute="route('roles.create')" :indexRoute="route('roles.index')"
                :deleteRoute="url('admin/roles')" :deleteAllRoute="route('roles.destroy', 0)">
                <div id="table_content_append">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </x-ui.table-card>
        </div>
    </div>
@endsection

@section('script-bottom')
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/dynamic-table.js') }}"></script>
@endsection