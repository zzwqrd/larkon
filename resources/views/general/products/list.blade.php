@extends('layouts.vertical', ['title' => 'Product List'])

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h4 class="card-title flex-grow-1 mb-0">All Product List</h4>

                    <div class="d-flex align-items-center gap-2">


                        <!-- Search Box -->
                        <div class="position-relative">
                            <input type="text" id="tableSearch" class="form-control" placeholder="Search records..."
                                style="padding-inline-start: 36px; border-radius: 8px; width: 250px;">
                            <i class="bx bx-search position-absolute text-muted"
                                style="inset-inline-start: 12px; top: 50%; transform: translateY(-50%); font-size: 18px;"></i>
                        </div>
                        <!-- Bulk Delete Button (Hidden by default) -->
                        <button type="button" id="bulkDeleteBtn"
                            class="btn btn-danger align-items-center justify-content-center"
                            style="border-radius: 8px; width: 38px; height: 38px; display: none !important;"
                            title="Delete Selected">
                            <i class="bx bx-trash fs-22"></i>
                        </button>
                        <!-- Add Button -->
                        <a href="{{ route('third', ['general', 'products', 'create']) }}"
                            class="btn btn-primary d-flex align-items-center justify-content-center"
                            style="border-radius: 8px; width: 38px; height: 38px;" title="Add Product">
                            <i class="bx bx-plus fs-20"></i>
                        </a>

                        <!-- Export Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-primary d-flex align-items-center justify-content-center" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false"
                                style="border-radius: 8px; width: 38px; height: 38px;" title="{{ __('messages.export') }}">
                                <i class="bx bx-download fs-20"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                                style="border-radius: 12px; min-width: 120px;">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#!"
                                        id="exportPrintBtn">
                                        <i class="bx bx-printer fs-18 text-muted"></i> <span
                                            class="fw-medium">{{ __('messages.print') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#!"
                                        id="exportExcelBtn">
                                        <i class="bx bx-file fs-18 text-muted"></i> <span
                                            class="fw-medium">{{ __('messages.excel') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Refresh Button -->
                        <button type="button" onclick="location.reload()"
                            class="btn btn-primary d-flex align-items-center justify-content-center"
                            style="border-radius: 8px; width: 38px; height: 38px;" title="Refresh">
                            <i class="bx bx-refresh fs-22"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th style="width: 20px;">
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="customCheck1">
                                            <label class="form-check-label" for="customCheck1"></label>
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
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                <img src="/images/product/p-1.png" alt="" class="avatar-md">
                                            </div>
                                            <div>
                                                <a href="#!" class="text-dark fw-medium fs-15">Black T-shirt</a>
                                                <p class="text-muted mb-0 mt-1 fs-13"><span>Size : </span>S , M , L , Xl
                                                </p>
                                            </div>
                                        </div>

                                    </td>
                                    <td>$80.00</td>
                                    <td>
                                        <p class="mb-1 text-muted"><span class="text-dark fw-medium">486 Item</span> Left
                                        </p>
                                        <p class="mb-0 text-muted">155 Sold</p>
                                    </td>
                                    <td> Fashion</td>
                                    <td> <span class="badge p-1 bg-light text-dark fs-12 me-1"><i
                                                class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 4.5</span>
                                        55 Review</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-primary btn-sm"><iconify-icon
                                                    icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon
                                                    icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                <img src="/images/product/p-2.png" alt="" class="avatar-md">
                                            </div>
                                            <div>
                                                <a href="#!" class="text-dark fw-medium fs-15">Olive Green Leather Bag</a>
                                                <p class="text-muted mb-0 mt-1 fs-13"><span>Size : </span>S , M </p>
                                            </div>
                                        </div>

                                    </td>
                                    <td>$136.00</td>
                                    <td>
                                        <p class="mb-1 text-muted"><span class="text-dark fw-medium">784 Item</span> Left
                                        </p>
                                        <p class="mb-0 text-muted">674 Sold</p>
                                    </td>
                                    <td> Hand Bag</td>
                                    <td> <span class="badge p-1 bg-light text-dark fs-12 me-1"><i
                                                class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 4.1</span>
                                        143 Review</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-primary btn-sm"><iconify-icon
                                                    icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon
                                                    icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                <img src="/images/product/p-3.png" alt="" class="avatar-md">
                                            </div>
                                            <div>
                                                <a href="#!" class="text-dark fw-medium fs-15">Women Golden Dress</a>
                                                <p class="text-muted mb-0 mt-1 fs-13"><span>Size : </span>S , M </p>
                                            </div>
                                        </div>

                                    </td>
                                    <td>$219.00</td>
                                    <td>
                                        <p class="mb-1 text-muted"><span class="text-dark fw-medium">769 Item</span> Left
                                        </p>
                                        <p class="mb-0 text-muted">180 Sold</p>
                                    </td>
                                    <td> Fashion</td>
                                    <td> <span class="badge p-1 bg-light text-dark fs-12 me-1"><i
                                                class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 4.4</span>
                                        174 Review</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-primary btn-sm"><iconify-icon
                                                    icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon
                                                    icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                <img src="/images/product/p-4.png" alt="" class="avatar-md">
                                            </div>
                                            <div>
                                                <a href="#!" class="text-dark fw-medium fs-15">Gray Cap For Men</a>
                                                <p class="text-muted mb-0 mt-1 fs-13"><span>Size : </span>S , M , L</p>
                                            </div>
                                        </div>

                                    </td>
                                    <td>$76.00</td>
                                    <td>
                                        <p class="mb-1 text-muted"><span class="text-dark fw-medium">571 Item</span> Left
                                        </p>
                                        <p class="mb-0 text-muted">87 Sold</p>
                                    </td>
                                    <td> Cap</td>
                                    <td> <span class="badge p-1 bg-light text-dark fs-12 me-1"><i
                                                class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 4.2</span>
                                        23 Review</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-primary btn-sm"><iconify-icon
                                                    icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon
                                                    icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                        </div>
                                    </td>
                                </tr>


                                <tr>
                                    <td>
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                <img src="/images/product/p-5.png" alt="" class="avatar-md">
                                            </div>
                                            <div>
                                                <a href="#!" class="text-dark fw-medium fs-15">Dark Green Cargo Pent</a>
                                                <p class="text-muted mb-0 mt-1 fs-13"><span>Size : </span>S , M , L , Xl
                                                </p>
                                            </div>
                                        </div>

                                    </td>
                                    <td>$110.00</td>
                                    <td>
                                        <p class="mb-1 text-muted"><span class="text-dark fw-medium">241 Item</span> Left
                                        </p>
                                        <p class="mb-0 text-muted">342 Sold</p>
                                    </td>
                                    <td> Fashion</td>
                                    <td> <span class="badge p-1 bg-light text-dark fs-12 me-1"><i
                                                class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 4.4</span>
                                        109 Review</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-primary btn-sm"><iconify-icon
                                                    icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon
                                                    icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                        </div>
                                    </td>
                                </tr>


                                <tr>
                                    <td>
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                <img src="/images/product/p-6.png" alt="" class="avatar-md">
                                            </div>
                                            <div>
                                                <a href="#!" class="text-dark fw-medium fs-15">Orange Multi Color
                                                    Headphone</a>
                                                <p class="text-muted mb-0 mt-1 fs-13"><span>Size : </span>S , M </p>
                                            </div>
                                        </div>

                                    </td>
                                    <td>$231.00</td>
                                    <td>
                                        <p class="mb-1 text-muted"><span class="text-dark fw-medium">821 Item</span> Left
                                        </p>
                                        <p class="mb-0 text-muted">231 Sold</p>
                                    </td>
                                    <td> Electronics</td>
                                    <td> <span class="badge p-1 bg-light text-dark fs-12 me-1"><i
                                                class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 4.2</span>
                                        200 Review</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-primary btn-sm"><iconify-icon
                                                    icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon
                                                    icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                        </div>
                                    </td>
                                </tr>


                                <tr>
                                    <td>
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                <img src="/images/product/p-7.png" alt="" class="avatar-md">
                                            </div>
                                            <div>
                                                <a href="#!" class="text-dark fw-medium fs-15">Kid's Yellow Shoes</a>
                                                <p class="text-muted mb-0 mt-1 fs-13"><span>Size : </span>18 , 19 , 20 , 21
                                                </p>
                                            </div>
                                        </div>

                                    </td>
                                    <td>$89.00</td>
                                    <td>
                                        <p class="mb-1 text-muted"><span class="text-dark fw-medium">321 Item</span> Left
                                        </p>
                                        <p class="mb-0 text-muted">681 Sold</p>
                                    </td>
                                    <td> Shoes</td>
                                    <td> <span class="badge p-1 bg-light text-dark fs-12 me-1"><i
                                                class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 4.5</span>
                                        321 Review</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-primary btn-sm"><iconify-icon
                                                    icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon
                                                    icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                <img src="/images/product/p-8.png" alt="" class="avatar-md">
                                            </div>
                                            <div>
                                                <a href="#!" class="text-dark fw-medium fs-15">Men Dark Brown Wallet</a>
                                                <p class="text-muted mb-0 mt-1 fs-13"><span>Size : </span>S , M</p>
                                            </div>
                                        </div>

                                    </td>
                                    <td>$132.00</td>
                                    <td>
                                        <p class="mb-1 text-muted"><span class="text-dark fw-medium">190 Item</span> Left
                                        </p>
                                        <p class="mb-0 text-muted">212 Sold</p>
                                    </td>
                                    <td> Wallet</td>
                                    <td> <span class="badge p-1 bg-light text-dark fs-12 me-1"><i
                                                class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 4.1</span>
                                        190 Review</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-primary btn-sm"><iconify-icon
                                                    icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon
                                                    icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                <img src="/images/product/p-9.png" alt="" class="avatar-md">
                                            </div>
                                            <div>
                                                <a href="#!" class="text-dark fw-medium fs-15">Sky Blue Sunglass</a>
                                                <p class="text-muted mb-0 mt-1 fs-13"><span>Size : </span>S , M </p>
                                            </div>
                                        </div>

                                    </td>
                                    <td>$77.00</td>
                                    <td>
                                        <p class="mb-1 text-muted"><span class="text-dark fw-medium">784 Item</span> Left
                                        </p>
                                        <p class="mb-0 text-muted">443 Sold</p>
                                    </td>
                                    <td> Sunglass</td>
                                    <td> <span class="badge p-1 bg-light text-dark fs-12 me-1"><i
                                                class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 3.5</span>
                                        298 Review</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-primary btn-sm"><iconify-icon
                                                    icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon
                                                    icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                <img src="/images/product/p-10.png" alt="" class="avatar-md">
                                            </div>
                                            <div>
                                                <a href="#!" class="text-dark fw-medium fs-15">Kid's Yellow T-shirt</a>
                                                <p class="text-muted mb-0 mt-1 fs-13"><span>Size : </span>S </p>
                                            </div>
                                        </div>

                                    </td>
                                    <td>$110.00</td>
                                    <td>
                                        <p class="mb-1 text-muted"><span class="text-dark fw-medium">650 Item</span> Left
                                        </p>
                                        <p class="mb-0 text-muted">365 Sold</p>
                                    </td>
                                    <td> Fashion</td>
                                    <td> <span class="badge p-1 bg-light text-dark fs-12 me-1"><i
                                                class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 4.1</span>
                                        156 Review</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-primary btn-sm"><iconify-icon
                                                    icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon
                                                    icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                <img src="/images/product/p-11.png" alt="" class="avatar-md">
                                            </div>
                                            <div>
                                                <a href="#!" class="text-dark fw-medium fs-15">White Rubber Band Smart Watch
                                                </a>
                                                <p class="text-muted mb-0 mt-1 fs-13"><span>Size : </span>S , M </p>
                                            </div>
                                        </div>

                                    </td>
                                    <td>$77.00</td>
                                    <td>
                                        <p class="mb-1 text-muted"><span class="text-dark fw-medium">98 Item</span> Left</p>
                                        <p class="mb-0 text-muted">241 Sold</p>
                                    </td>
                                    <td> Electronics</td>
                                    <td> <span class="badge p-1 bg-light text-dark fs-12 me-1"><i
                                                class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 3.4</span>
                                        201 Review</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-primary btn-sm"><iconify-icon
                                                    icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon
                                                    icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="form-check ms-1">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                <img src="/images/product/p-12.png" alt="" class="avatar-md">
                                            </div>
                                            <div>
                                                <a href="#!" class="text-dark fw-medium fs-15">Men Brown Leather Shoes </a>
                                                <p class="text-muted mb-0 mt-1 fs-13"><span>Size : </span>40 , 41 , 42 , 43
                                                </p>
                                            </div>
                                        </div>

                                    </td>
                                    <td>$222.00</td>
                                    <td>
                                        <p class="mb-1 text-muted"><span class="text-dark fw-medium">176 Item</span> Left
                                        </p>
                                        <p class="mb-0 text-muted">658 Sold</p>
                                    </td>
                                    <td> Shoes</td>
                                    <td> <span class="badge p-1 bg-light text-dark fs-12 me-1"><i
                                                class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 4.1</span>
                                        370 Review</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-primary btn-sm"><iconify-icon
                                                    icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon
                                                    icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="align-middle fs-18"></iconify-icon></a>
                                        </div>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>
                    <!-- end table-responsive -->
                </div>
                <div class="card-footer border-top">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end mb-0" id="tablePagination">
                            <!-- Pagination will be dynamically generated by Javascript -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('script-bottom')
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/xlsx.full.min.js') }}"></script>
    <script>
        // إرسال الترجمات إلى الجافاسكربت الخارجي
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