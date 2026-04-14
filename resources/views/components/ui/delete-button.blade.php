@props(['id', 'href' => null, 'route' => null])

<a href="#!" class="btn btn-soft-danger btn-sm delete-btn" 
   data-id="{{ $id }}" 
   data-url="{{ $route ?? $href }}"
   title="Delete">
    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
</a>
