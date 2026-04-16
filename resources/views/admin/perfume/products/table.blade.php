<table class="table align-middle mb-0 table-hover table-centered">
    <thead class="bg-light-subtle">
        <tr>
            <th style="width: 20px;">
                <div class="form-check ms-1">
                    <input type="checkbox" class="form-check-input" id="checkAll">
                    <label class="form-check-label" for="checkAll"></label>
                </div>
            </th>
            <th>Product Name & Size</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Category</th>
            <th>Rating</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="tableBody">
        @forelse ($products as $product)
            <tr>
                <td>
                    <div class="form-check ms-1">
                        <input type="checkbox" class="form-check-input product-checkbox" value="{{ $product->id }}">
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                            <img src="/images/product/{{ $product->image }}" alt="" class="avatar-md">
                        </div>
                        <div>
                            <a href="#!" class="text-dark fw-medium fs-15">{{ $product->name }}</a>
                            <p class="text-muted mb-0 mt-1 fs-13"><span>Size : </span>{{ $product->size }}</p>
                        </div>
                    </div>
                </td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>
                    <p class="mb-1 text-muted"><span class="text-dark fw-medium">{{ $product->stock }} Item</span> Left</p>
                    <p class="mb-0 text-muted">{{ $product->sold }} Sold</p>
                </td>
                <td>{{ $product->category }}</td>
                <td>
                    <span class="badge p-1 bg-light text-dark fs-12 me-1">
                        <i class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> {{ $product->rating }}
                    </span>
                    {{ $product->reviews_count }} Review
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <a href="#!" class="btn btn-light btn-sm">
                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                        </a>

                        <x-ui.edit-button :route="route('admin.products.edit', $product->id)" />

                        <x-ui.delete-button :id="$product->id" :route="route('admin.products.destroy', $product->id)" />
                    </div>
                </td>
            </tr>
        @empty
            <x-ui.empty-table :cols="7" />
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    <x-ui.table-pagination :items="$products" />
</div>