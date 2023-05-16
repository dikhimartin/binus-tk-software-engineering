@extends('layouts.backend.auth')

@section('content')
    <!--begin::Form-->
    <form class="form w-100" role="form" method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}        
        <div class="text-center mb-11">
            <a href="/"><img alt="Logo" src="{{ URL::asset('assets/media/logos/logo-web.svg') }}" class="h-55px mb-10"></a>
            <h2 class="text-dark fw-bolder mb-3">{{ __('passwords.recover_password') }}</h2>
            <div class="text-gray-500 fw-semibold fs-3">{{ __('passwords.send_password_reset_link_detail') }}</div>
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

        <div class="fv-row mb-8 ">
            <input id="email" placeholder="Email" type="email" class="form-control { $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" >
            @if ($errors->has('email'))
                <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>    

        <div class="d-grid mb-10">
            <button type="submit" id="submitBtn" class="btn btn-primary">
                <span class="indicator-label"> {{ __('passwords.send_password_reset_link') }}</span>
                <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
    </form>
@endsection
