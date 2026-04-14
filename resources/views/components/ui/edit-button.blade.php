@props(['href' => '#!', 'route' => null])

<a href="{{ $route ?? $href }}" class="btn btn-soft-primary btn-sm" title="Edit">
    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
</a>
