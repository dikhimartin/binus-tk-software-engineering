@extends('layouts.backend.auth')

@section('content')
    <!--begin::Form-->
    <form class="form w-100" method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">    
        <div class="text-center mb-11">
            <a href="/"><img alt="Logo" src="{{ URL::asset('assets/media/logos/logo-web.svg') }}" class="h-55px mb-10"></a>
            <h2 class="text-dark fw-bolder mb-3">{{ __('passwords.recover_password') }}</h2>
        </div>


        @if (count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <span>{{ $error }}<br></span>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="fv-row mb-8">
            <input id="email" placeholder="Email" type="email" class="form-control { $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" >
        </div>

        <div class="fv-row mb-8" data-kt-password-meter="true">
            <div class="mb-1">
                <div class="position-relative mb-3">
                    <input class="form-control bg-transparent" type="password" placeholder="{{ __('passwords.password') }}" name="password" autocomplete="new-password" />
                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                        <i class="bi bi-eye-slash fs-2"></i>
                        <i class="bi bi-eye fs-2 d-none"></i>
                    </span>
                </div>
                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                </div>
            </div>
            <div class="text-muted">{{ __('passwords.rules_password_description') }}</div>
        </div>

        <div class="fv-row mb-8">
            <input placeholder="{{__('main.confirm')}} {{ __('passwords.password') }}" name="password_confirmation" type="password" autocomplete="off" class="form-control bg-transparent" />
        </div>

        <div class="d-grid mb-10">
            <button type="submit" id="submitBtn" class="btn btn-primary">
                <span class="indicator-label">   {{ __('passwords.reset_password') }}</span>
                <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>

    </form>
@endsection