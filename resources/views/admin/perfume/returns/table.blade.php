<table class="table align-middle mb-0 table-hover table-centered">
    <thead class="bg-light-subtle">
        <tr>
            <th style="width: 20px;"><div class="form-check ms-1"><input type="checkbox" class="form-check-input" id="checkAll"></div></th>
            <th>{{ __('messages.return_requests') }}</th>
            <th>{{ __('messages.status') }}</th>
            <th>{{ __('messages.action') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($returns as $return)
            <tr>
                <td><div class="form-check ms-1"><input type="checkbox" class="form-check-input product-checkbox" value="{{ $return->id }}"></div></td>
                <td><p class="text-dark fw-medium fs-14 mb-0">{{ $return->name ?? $return->title ?? '' }}</p></td>
                <td><span class="badge bg-success-subtle text-success py-1 px-2">{{ __('messages.active') }}</span></td>
                <td>
                    <div class="d-flex gap-2">
                        <x-ui.edit-button :route="route('admin.returns.edit', $return->id)" />
                        <x-ui.delete-button :id="$return->id" :route="route('admin.returns.destroy', $return->id)" />
                    </div>
                </td>
            </tr>
        @empty
            <x-ui.empty-table :cols="4" />
        @endforelse
    </tbody>
</table>
<x-ui.table-pagination :items="$returns" />
