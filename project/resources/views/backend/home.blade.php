@extends('layouts.backend.app')
@section('sidebarActive', $controller)

<!--begin::Vendor Stylesheets(used for this page only)-->
@push('private_css')
    <style>
        .card-product:hover {
            transform: translateY(-5px);
            box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.1s ease-in-out;
        }
    </style>
@endpush

@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{!! $pages_title !!}</h1>
            </div>
        </div>
    </div>

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="d-flex flex-column flex-xl-row">
                <div class="row d-flex flex-row-fluid me-xl-9 mb-10 mb-xl-0">
                    <div class="col-md-12 ">
                        <!-- <div class="input-group mb-8">
                            <input type="text" id="search-query" class="form-control" placeholder="{{__('main.search')}} {{__('main.product')}}">
                        </div>
                        <div id="room-list-container" class="row"></div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection