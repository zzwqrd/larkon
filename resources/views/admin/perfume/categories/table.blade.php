<table class="table align-middle mb-0 table-hover table-centered">
    <thead class="bg-light-subtle">
        <tr>
            <th style="width: 20px;"><div class="form-check ms-1"><input type="checkbox" class="form-check-input" id="checkAll"></div></th>
            <th>{{ __('messages.category') }}</th>
            <th>{{ __('messages.status') }}</th>
            <th>{{ __('messages.action') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($categories as $category)
            <tr>
                <td><div class="form-check ms-1"><input type="checkbox" class="form-check-input product-checkbox" value="{{ $category->id }}"></div></td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        @if($category->image)
                            <img src="/storage/{{ $category->image }}" alt="" class="avatar-sm rounded">
                        @endif
                        <p class="text-dark fw-medium fs-14 mb-0">{{ $category->name }}</p>
                    </div>
                </td>
                <td>{{ $category->parent->name ?? 'None' }}</td>
                <td><span class="badge bg-info-subtle text-info">{{ ucfirst($category->type) }}</span></td>
                <td>
                    @if($category->is_active)
                        <span class="badge bg-success-subtle text-success py-1 px-2">{{ __('messages.active') }}</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger py-1 px-2">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <x-ui.edit-button :route="route('admin.category.edit', $category->id)" />
                        <x-ui.delete-button :id="$category->id" :route="route('admin.category.destroy', $category->id)" />
                    </div>
                </td>
            </tr>
        @empty
            <x-ui.empty-table :cols="6" />
        @endforelse
    </tbody>
</table>
<x-ui.table-pagination :items="$categories" />
