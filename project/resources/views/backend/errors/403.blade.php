@extends('layouts.backend.errors')
@section('content')
    <div class="card-body py-15 py-lg-20">
        <h1 class="fw-bolder fs-2hx text-gray-900 mb-4">{{  __('main.403_page_title')}}</h1>
        <div class="fw-semibold fs-3 text-gray-500 mb-7">{{  __('main.403_page_description')}}</div>
        <div class="mb-3">
            <h1 class="error-code text-danger">403</h1>
        </div>
        <div class="mb-0">
            <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(),URL::to( 'admin/dashboard' ))}}" 
            class="btn btn-dark">{{__('main.back_to_home')}}</a>
        </div>
    </div>
@endsection