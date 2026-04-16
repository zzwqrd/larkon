<table class="table align-middle mb-0 table-hover table-centered">
    <thead class="bg-light-subtle">
        <tr>
            <th style="width: 20px;">
                <div class="form-check ms-1"><input type="checkbox" class="form-check-input" id="checkAll"></div>
            </th>
            <th>{{ __('messages.brands') }}</th>
            <th>Country</th>
            <th>Founded Year</th>
            <th>{{ __('messages.status') }}</th>
            <th>{{ __('messages.action') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($brands as $brand)
            <tr>
                <td><div class="form-check ms-1"><input type="checkbox" class="form-check-input product-checkbox" value="{{ $brand->id }}"></div></td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        @if($brand->logo)
                            <img src="/storage/{{ $brand->logo }}" alt="" class="avatar-sm rounded">
                        @endif
                        <p class="text-dark fw-medium fs-14 mb-0">{{ $brand->name }}</p>
                    </div>
                </td>
                <td>{{ $brand->country_of_origin }}</td>
                <td>{{ $brand->founded_year }}</td>
                <td>
                    @if($brand->is_active)
                        <span class="badge bg-success-subtle text-success py-1 px-2">{{ __('messages.active') }}</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger py-1 px-2">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <x-ui.edit-button :route="route('admin.brands.edit', $brand->id)" />
                        <x-ui.delete-button :id="$brand->id" :route="route('admin.brands.destroy', $brand->id)" />
                    </div>
                </td>
            </tr>
        @empty
            <x-ui.empty-table :cols="6" />
        @endforelse
    </tbody>
</table>
<x-ui.table-pagination :items="$brands" />