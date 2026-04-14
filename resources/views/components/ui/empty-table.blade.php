@props(['cols' => 1])

<tr>
    <td colspan="{{ $cols }}" class="text-center py-5">
        <div class="text-muted">
            <div class="mb-3">
                <iconify-icon icon="solar:box-minimalistic-broken" class="fs-48 text-muted opacity-50"></iconify-icon>
            </div>
            <h5 class="fw-semibold">No Data Found</h5>
            <p class="mb-0">There are no records to display at the moment.</p>
        </div>
    </td>
</tr>