@extends('layouts.vertical', ['title' => __('messages.roles_list')])

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <x-ui.table-card 
                :title="__('messages.roles_list')" 
                :addRoute="route('admin.roles.create')"
                :indexRoute="route('admin.roles.index')"
                :deleteRoute="url('admin/roles')"
                :deleteAllRoute="route('admin.roles.bulkDelete')"
            >
                <div id="table_content_append">
                    {{-- AJAX will load the _table.blade.php content here --}}
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
    <script src="{{ asset('js/xlsx.full.min.js') }}"></script>
    <script>
        window.TableTranslations = {
            are_you_sure: '{{ __('messages.are_you_sure') }}',
            about_to_delete: '{{ __('messages.about_to_delete', ['count' => ':count']) }}',
            yes_delete: '{{ __('messages.yes_delete') }}',
            cancel: '{{ __('messages.cancel') }}',
            deleted: 'Deleted!',
            success_msg: 'Item has been deleted successfully.',
            success_bulk_msg: 'Your selection has been deleted successfully.'
        };
    </script>
    <script src="{{ asset('js/dynamic-table.js') }}"></script>
@endsection