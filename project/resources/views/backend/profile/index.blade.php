@extends('layouts.backend.app')
@section('sidebarActive', $controller)

@section('content')
    <!--begin::Toolbar Component-->
	@component('backend.components.toolbar', ['pages_title' => $pages_title, 'sub_menu' => null, 'sub_menu_link' => null])
	@endcomponent	

	<!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
		<!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <form id="kt_user_profile_form" class="form d-flex flex-column flex-lg-row">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <input type="hidden" name="old_password">
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                    <div class="card card-flush py-4">
                        <div class="card-body text-center">
                            <style>.image-input-placeholder { background-image: url({{ $data_user->avatar_absolute_path != '' ? $data_user->avatar_absolute_path : URL::asset('images/profile/anonymous.png') }}); } [data-bs-theme="dark"] .image-input-placeholder { background-image: url('assets/media/svg/files/blank-image-dark.svg'); }</style>
                            <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                                <div class="image-input-wrapper w-150px h-150px"></div>
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                                    <input type="hidden" name="avatar_remove" />
                                </label>
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                            </div>

                            <div class="text-muted fs-7">
                                <div class="alert alert-primary d-flex align-items-center p-5 mb-10">
                                    <div class="d-flex flex-column">
                                        <span>{{ __('main.info_image_pixels', ['value' => "150 x 150"]) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_add_product_general">{{__('main.profiles')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_advanced">{{__('main.account')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="kt_ecommerce_add_product_general" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <div class="card card-flush py-4">
                                    <div class="card-body">
                                        <div class="mb-10 fv-row fv-plugins-icon-container">
                                            <label class="required fw-semibold fs-6 mb-2">{{ __('main.name') }}</label>
                                            <input type="text" name="name" value="{{ $data_user->name }}" class="form-control  mb-3 mb-lg-0" placeholder="{{ __('main.name') }}"  />
                                        </div>
                                        <div class="mb-10 fv-row fv-plugins-icon-container">
                                            <label class="required fw-semibold fs-6 mb-2">{{ __('main.email') }}</label>
                                            <input type="email" name="email" value="{{ $data_user->email }}" class="form-control mb-3 mb-lg-0" placeholder="{{ __('main.email') }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--begin::Tab pane-->
                        <div class="tab-pane" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <div class="card card-flush py-4">
                                    <div class="card-body">
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">{{ __('main.username') }}</label>
                                            <input type="email" name="username" value="{{ $data_user->username }}" class="form-control mb-3 mb-lg-0" placeholder="{{ __('main.username') }}" />
                                        </div>

                                        <div class="fv-row mb-7">
                                            <div class="alert alert-primary  d-flex align-items-center">
                                                <div class="d-flex flex-column">
                                                    <span>{{__('passwords.change_password_remarks')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- password-->
                                        <div class="mb-10 fv-row" data-kt-password-meter="true" >
                                            <div class="mb-1">
                                                <label class="form-label fw-semibold fs-6 mb-2">
                                                {{ __('passwords.password') }}
                                                </label>
                                                <div class="position-relative mb-3">
                                                    <input class="form-control form-control-lg" type="password" placeholder="" name="password" autocomplete="new-password" />
                                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                                        <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                        <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                                </div>
                                            </div>
                                            <div class="text-muted">
                                                {{ __('passwords.rules_password_description') }}
                                            </div>
                                            <!-- confirm password-->
                                            <label class="form-label fw-semibold fs-6 mb-2">{{__('main.confirm')}} {{ __('passwords.password') }}</label>
                                            <input class="form-control form-control-lg" type="password" placeholder="" name="password_confirmation" autocomplete="off" /> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="/" class="btn btn-light me-5">{{ __('main.cancel') }}</a>
                        <button type="submit" id="kt_user_profile_submit" class="btn btn-primary">
                            <span class="indicator-label">{{ __('main.save_changes') }}</span>
                            <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </div>
            </form>
            <!--begin::Modal Component-->
            @component('backend.components.modal', ['modal_size' => 'modal-md', 'modal_id' => 'modal_confirm_password'])
                @section('modal_content')
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2">{{ __('passwords.old_password') }}</label>
                    <input type="password" class="form-control" name="confirm_password" />
                </div>
                @endsection
                @section('modal_footer')
                    <a href="javascript:void(0)" id="kt_user_confirm_password_submit" class="btn btn-primary">
                        <span class="indicator-label">{{ __('main.confirm') }}</span>
                    </a>
                @endsection
            @endcomponent
		</div>
	</div>
@endsection

<!--begin::Vendors Javascript(used for this page only)-->
@push('private_js')
    <script>
        "use strict";   
        
        const URL_API = `{{ url('admin/profile') }}`

        // Class definition
        var KTAppSaveProfile = function () {

            // Private functions
            // Submit form handler
            const handleSubmit = () => {
                // Define variables
                let validator;

                // Get elements
                const form = document.getElementById('kt_user_profile_form');
                const submitButton = document.getElementById('kt_user_profile_submit');
                const submitConfirm = document.getElementById('kt_user_confirm_password_submit');

				// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
                validator = FormValidation.formValidation(
					form,
					{
						fields: {
							'name': {
								validators: {
									notEmpty: {
										message: `Name {{ __('main.is_required') }}`
									}
								}
							},
							'role_id': {
								validators: {
									notEmpty: {
										message: `{{ __('main.user_role') }} {{ __('main.is_required') }}`
									}
								}
							},
							'email': {
								validators: {
									notEmpty: {
										message: `Email {{ __('main.is_required') }}`
									},
									emailAddress: {
										multiple: true,
										message: `{{ __('main.not_valid_email') }}`,
									},
								}
							},
							'username': {
								validators: {
									notEmpty: {
										message: `Username {{ __('main.is_required') }}`
									},
									stringLength: {
										min: 6,
										max: 20,
										message: 'The username must be more than 6 and less than 20 characters long'
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
							}
						},
						plugins: {
							trigger: new FormValidation.plugins.Trigger(),
							bootstrap: new FormValidation.plugins.Bootstrap5({
								rowSelector: '.fv-row',
								eleInvalidClass: '',
								eleValidClass: ''
							})
						}
					}
				);

                // Handle submit button
                submitButton.addEventListener('click', e => {
                    e.preventDefault();

                    // Validate form before submit
                    if (validator) {
                        validator.validate().then(function (status) {
                            console.log('validated!');

							if (status == 'Valid') {
								submitButton.setAttribute('data-kt-indicator', 'on');
								
								// Disable submit button whilst loading
								submitButton.disabled = true;

								// set data
								var url = URL_API;
								var formData = new FormData(document.querySelector('#kt_user_profile_form'));
								submitForm(url, "PUT", formData);								

							} else {
								ToastrError(`{{ __('main.sorry_looks_like_there_are_some_errors_detected_please_try_again') }}`);
							}

                        });
                    }
                })

                submitConfirm.addEventListener('click', e => {
                    e.preventDefault();
                    var confirm_password = $('[name="confirm_password"]').val();
                    if (confirm_password == ''){
                        return;
                    }
                    $('[name="old_password"]').val(confirm_password);

        			// set data
                    var url = URL_API;
                    var formData = new FormData(document.querySelector('#kt_user_profile_form'));
                    submitForm(url, "PUT", formData);	

                    $('#modal_confirm_password').modal('hide');		
                })


				// Send data to server | POST | PUT
				function submitForm(url, method, formData) {
					submitButton.disabled = false;
					$.ajaxSetup({
						headers: {
							'X-HTTP-Method-Override': method
						}
					});
					$.ajax({
						url : url,
						type: "POST",
						data: formData,
						contentType: false,
						processData: false,
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},						
						dataType: "JSON",
						success: function(response){
							var messages = "{{ __('main.data_added_succesfully') }}";
							if(method.toLowerCase() == "put"){
								messages = "{{ __('main.data_succesfully_changed') }}";
							}
							submitButton.removeAttribute('data-kt-indicator');
                            SwalSuccess(messages).then(function (result) {
								if (result.isConfirmed) {
                                    reset_form_password();
									submitButton.disabled = false;
								}
							}); 
						},
						error: function (jqXHR, textStatus, errorThrown) {
                            if(jqXHR.responseJSON != undefined && jqXHR.responseJSON.status.code == 400 && jqXHR.responseJSON.status.message == "old_password_required"){
                                submitButton.removeAttribute('data-kt-indicator');
                                submitButton.disabled = false;
                                show_modal_confirm_password()	
                                return;
                            }else if (jqXHR.status == 400){
                                ToastrErrorValidation(jqXHR);
                            }else if (jqXHR.status == 401){
                                SwalError(errorThrown)
                                show_modal_confirm_password()	
                            }else{
                                SwalError(errorThrown)
                            }
							submitButton.removeAttribute('data-kt-indicator');
							submitButton.disabled = false;
                            return;
						}
					}); 
                    return
				}	

                function reset_form_password(){
                    $('#kt_user_profile_form input[name="confirm_password"]').val('');
                    $('#kt_user_profile_form input[name="old_password"]').val('');
                    $('#kt_user_profile_form input[name="password"]').val('');
                    $('#kt_user_profile_form input[name="password_confirmation"]').val('');
                }

                function show_modal_confirm_password(){
                    $('#modal_confirm_password_header_title').text(`{{__('passwords.old_password_required')}}`);
                    $('#kt_user_profile_form input[name="confirm_password"]').val('');
                    $('#kt_user_profile_form input[name="old_password"]').val('');
                    $('#modal_confirm_password').modal('show');		
                }
            }

            // Public methods
            return {
                init: function () {
                    handleSubmit();
                }
            };
        }();

        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            KTAppSaveProfile.init();
        });
    </script>
@endpush

