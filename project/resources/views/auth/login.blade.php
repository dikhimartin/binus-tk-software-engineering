@extends('layouts.backend.auth')

@section('content')
    <!--begin::Form-->
    <form action="/login" class="form w-100" id="loginform" role="form" method="POST">
        {{ csrf_field() }}        
        <div class="text-center mb-11">
            <a href="/"><img alt="Logo" src="{{ URL::asset('assets/media/logos/logo-web.svg') }}" class="h-55px mb-10"></a>
            <h2 class="text-dark fw-bolder mb-3">Selamat Datang Kembali</h2>
            <div class="text-gray-500 fw-semibold fs-3">Silahkan Login dengan akun Anda</div>
        </div>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <span>{{ $error }}<br></span>
                @endforeach
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <p> {{ session('error') }}</p>
            </div>
        @endif


        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="fv-row mb-8">
            <input type="text" placeholder="Email / Username" name="email_or_username" autocomplete="off" class="form-control bg-transparent" value="{{ old('email_or_username') }}"/>
        </div>
        
        <div class="fv-row mb-8">
            <input type="password" placeholder="Password" name="password" autocomplete="new-password" class="form-control bg-transparent" />
        </div>

        @if (config('app.env') == 'production')
            <div class="fv-row mb-3">
                {!! NoCaptcha::renderJs() !!}
                {!! NoCaptcha::display() !!}
            </div>
        @endif
        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
            <div></div>
            <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(),URL::to( 'password/reset' ))}}" class="link-primary">{{ __('passwords.forgot_password') }} ?</a>
        </div>

        <div class="d-grid mb-10">
            <button type="submit" id="submitBtn" class="btn btn-primary">
                <span class="indicator-label">{{__('main.login')}}</span>
                <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
        <div class="text-gray-500 text-center fw-semibold fs-6">{{ __('main.dont_have_an_account') }} 
        <a href="/register" class="link-primary">{{ __('main.signup') }}</a></div>
    </form>
@endsection

@push('private_js')
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            document.querySelector('#submitBtn').disabled = true;
        });
    </script>
@endpush