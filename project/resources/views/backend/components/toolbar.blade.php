<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{!! $pages_title !!}</h1>
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(),URL::to( 'admin/dashboard' ))}}" class="text-muted text-hover-primary">{{ __('main.dashboard') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                @isset($sub_menu)
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ $sub_menu_link }}" class="text-muted text-hover-primary">{!! $sub_menu !!}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                @endisset
                <li class="breadcrumb-item text-muted">{!! $pages_title !!}</li>
            </ul>
        </div>
        @isset($content_slot)
            {!! $content_slot !!}
        @endisset
    </div>
</div>

