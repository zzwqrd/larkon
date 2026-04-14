@props([
    'title' => '',
    'addRoute' => '',
    'indexRoute' => '',
    'deleteRoute' => '',
    'deleteAllRoute' => '',
    'searchPlaceholder' => 'Search records...'
])

<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h4 class="card-title flex-grow-1 mb-0">{{ $title }}</h4>

        <div class="d-flex align-items-center gap-2">
            <!-- Search Box -->
            <div class="position-relative">
                <input type="text" id="tableSearch" class="form-control" placeholder="{{ $searchPlaceholder }}"
                    style="padding-inline-start: 36px; border-radius: 8px; width: 250px;">
                <i class="bx bx-search position-absolute text-muted"
                    style="inset-inline-start: 12px; top: 50%; transform: translateY(-50%); font-size: 18px;"></i>
            </div>
            
            @if($deleteAllRoute)
            <!-- Bulk Delete Button (Hidden by default) -->
            <button type="button" id="bulkDeleteBtn" data-route="{{ $deleteAllRoute }}"
                class="btn btn-danger align-items-center justify-content-center"
                style="border-radius: 8px; width: 38px; height: 38px; display: none !important;"
                title="Delete Selected">
                <i class="bx bx-trash fs-22"></i>
            </button>
            @endif


            @if($addRoute)
            <!-- Add Button -->
            <a href="{{ $addRoute }}"
                class="btn btn-primary d-flex align-items-center justify-content-center"
                style="border-radius: 8px; width: 38px; height: 38px;" title="Add">
                <i class="bx bx-plus fs-20"></i>
            </a>
            @endif

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
            <div id="table_content_append" 
                 data-index-route="{{ $indexRoute }}" 
                 data-delete-route="{{ $deleteRoute }}"
                 data-bulk-delete-route="{{ $deleteAllRoute }}">
                {{ $slot }}
            </div>
        </div>
    </div>
    <div class="card-footer border-top">
        @if(isset($footer))
            {{ $footer }}
        @else
            <ul class="pagination justify-content-end mb-0" id="tablePagination"></ul>
        @endif
    </div>
</div>
