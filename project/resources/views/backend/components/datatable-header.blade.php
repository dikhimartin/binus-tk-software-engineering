<div class="card-header border-0 pt-6">
    <!--begin::Card title-->
    <div class="card-title">
        <!--begin::Search-->
        <div class="d-flex align-items-center position-relative my-1">
            <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span class="path1"></span><span class="path2"></span></i>
            <input type="text" data-kt-docs-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="{{ __('main.search_data') }}"/>
        </div>
    </div>

    <!--begin::Card toolbar-->
    <div class="card-toolbar">
        <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
            @isset($filter_slot)
            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                <span class="svg-icon svg-icon-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor" />
                        </svg>
                    </span>
                    Filter
                </button>
                {!! $filter_slot !!}
            @endisset
            @isset($button_slot)
                {!! $button_slot !!}
            @endisset
        </div>
        <!--begin::Group actions-->
        <div class="d-flex justify-content-end align-items-center d-none" data-kt-docs-table-toolbar="selected">
            <div class="fw-bold me-5">
            <span class="me-2" data-kt-docs-table-select="selected_count"></span>{{ __('main.selected') }}</div>
            <button type="button" class="btn btn-danger" data-kt-docs-table-select="delete_selected">{{ __('main.delete_selected') }}</button>
        </div>
    </div>
</div>