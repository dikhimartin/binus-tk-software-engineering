@extends('layouts.backend.auth')

@section('content')
    <!--begin::Form-->
    <!-- <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form"> -->
    <form class="form-horizontal"  role="form" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}    
        <div class="text-center mb-11">
            <a href="/"><img alt="Logo" src="{{ URL::asset('assets/media/logos/logo-web.svg') }}" class="h-55px mb-10"></a>
            <h2 class="text-dark fw-bolder mb-3">Daftar Akun</h2>
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
            <input type="text" placeholder="Email" name="email" autocomplete="off" class="form-control bg-transparent" value="{{ old('email') }}"/>
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

        @if (config('app.env') == 'production')
            <div class="fv-row mb-3">
                {!! NoCaptcha::renderJs() !!}
                {!! NoCaptcha::display() !!}
            </div>
        @endif

        <!-- <div class="fv-row mb-8">
            <label class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="toc" value="1" />
                <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">I Accept the
                <a href="javascript:void(0)" class="ms-1 link-primary">Terms</a></span>
            </label>
        </div> -->
        
        <div class="d-grid mb-10">
            <button type="submit" id="submitBtn" class="btn btn-primary">
                <span class="indicator-label">Daftar</span>
                <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
        <div class="text-gray-500 text-center fw-semibold fs-6">Sudah memiliki akun?
        <a href="/login" class="link-primary fw-semibold">{{ __('main.login') }}</a></div>
    </form>
@endsection
@push('private_js')
    <script>
        "use strict";

        // Class definition
        var KTSignup = function() {
            // Elements
            var form;
            var submitButton;
            var validator;
            var passwordMeter;

            // Handle form
            var handleForm  = function(e) {
                // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
                validator = FormValidation.formValidation(
                    form,
                    {
                        fields: {
                            'email': {
                                validators: {
                                    regexp: {
                                        regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                        message: 'The value is not a valid email address',
                                    },
                                    notEmpty: {
                                        message: 'Email {{ __('main.is_required') }}'
                                    }
                                }
                            },
							'password': {
								validators: {
									stringLength: {
										min: 6,
										message: `{{ __('passwords.password_characters_length', ['value' => "6"]) }}`
									},
									callback: {
										message: `{{ __('auth.please_enter_valid_password') }}`,
										callback: function (input) {
											if (input.value.length > 0) {
												// Check if password_confirmation field is empty
												const password_confirmation_input = form.querySelector('[name="password_confirmation"]');
												if (password_confirmation_input.value.length === 0) {
													password_confirmation_input.setCustomValidity('{{ __("main.confirm") }} {{ __("passwords.password") }} {{ __("main.is_required") }}');
												} else {
													password_confirmation_input.setCustomValidity('');
												}
												return validatePassword();
											}
										}
									}
								}
							},
							'password_confirmation': {
								validators: {
									identical: {
										compare: function () {
											return form.querySelector('[name="password"]').value;
										},
										message: `{{ __('passwords.the_password_and_its_confirm_are_not_the_same') }}`
									}
								}
							},
                            'toc': {
                                validators: {
                                    notEmpty: {
                                        message: 'You must accept the terms and conditions'
                                    }
                                }
                            }
                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger({
                                event: {
                                    password: false
                                }  
                            }),
                            bootstrap: new FormValidation.plugins.Bootstrap5({
                                rowSelector: '.fv-row',
                                eleInvalidClass: '',  // comment to enable invalid state icons
                                eleValidClass: '' // comment to enable valid state icons
                            })
                        }
                    }
                );

                // Handle form submit
                submitButton.addEventListener('click', function (e) {
                    e.preventDefault();

                    validator.revalidateField('password');

                    validator.validate().then(function(status) {
                        if (status == 'Valid') {
                            // Show loading indication
                            submitButton.setAttribute('data-kt-indicator', 'on');

                            // Disable button to avoid multiple click 
                            submitButton.disabled = true;

                            // Hide loading indication
                            submitButton.removeAttribute('data-kt-indicator');

                            // Enable button
                            submitButton.disabled = false;
                            form.reset();  // reset form                    
                            passwordMeter.reset();  // reset password meter
                            form.submit(); // submit form

                        } else {
                            ToastrError(`{{ __('main.sorry_looks_like_there_are_some_errors_detected_please_try_again') }}`);
                        }
                    });
                });

                // Handle password input
                form.querySelector('input[name="password"]').addEventListener('input', function() {
                    if (this.value.length > 0) {
                        validator.updateFieldStatus('password', 'NotValidated');
                    }
                });
            }

            // Password input validation
            var validatePassword = function() {
                return (passwordMeter.getScore() === 80);
            }

            // Public functions
            return {
                // Initialization
                init: function() {
                    // Elements
                    form = document.querySelector('#kt_sign_up_form');
                    submitButton = document.querySelector('#submitBtn');
                    passwordMeter = KTPasswordMeter.getInstance(form.querySelector('[data-kt-password-meter="true"]'));

                    handleForm ();
                }
            };
        }();

        // On document ready
        KTUtil.onDOMContentLoaded(function() {
            KTSignup.init();
        });



    </script>
@endpush
