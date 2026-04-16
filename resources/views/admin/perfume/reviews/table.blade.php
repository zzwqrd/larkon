<table class="table align-middle mb-0 table-hover table-centered">
    <thead class="bg-light-subtle">
        <tr>
            <th style="width: 20px;"><div class="form-check ms-1"><input type="checkbox" class="form-check-input" id="checkAll"></div></th>
            <th>{{ __('messages.reviews') }}</th>
            <th>{{ __('messages.status') }}</th>
            <th>{{ __('messages.action') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($reviews as $review)
            <tr>
                <td><div class="form-check ms-1"><input type="checkbox" class="form-check-input product-checkbox" value="{{ $review->id }}"></div></td>
                <td><p class="text-dark fw-medium fs-14 mb-0">{{ $review->name ?? $review->title ?? '' }}</p></td>
                <td><span class="badge bg-success-subtle text-success py-1 px-2">{{ __('messages.active') }}</span></td>
                <td>
                    <div class="d-flex gap-2">
                        <x-ui.edit-button :route="route('admin.reviews.edit', $review->id)" />
                        <x-ui.delete-button :id="$review->id" :route="route('admin.reviews.destroy', $review->id)" />
                    </div>
                </td>
            </tr>
        @empty
            <x-ui.empty-table :cols="4" />
        @endforelse
    </tbody>
</table>
<x-ui.table-pagination :items="$reviews" />
