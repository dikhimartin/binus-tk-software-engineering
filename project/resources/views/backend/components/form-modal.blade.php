<!--begin::Modal - Add Data-->
<div class="modal" id="{{ $modal_id }}_trigger" tabindex="-1" aria-hidden="true" style="display:none;">
    <!--begin::Modal dialog-->
    <!-- size posibility value : modal-sm, modal-md, modal-lg, modal-xl  -->
    <div class="modal-dialog modal-dialog-centered {{ $modal_size }}">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" id="{{ $modal_id }}" enctype="multipart/form-data">
                <meta name="csrf-token" content="{{ csrf_token() }}">

                <!--begin::Modal header-->
                <div class="modal-header" id="{{ $modal_id }}_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold" id="{{ $modal_id }}_header_title"></h2>
                    <!--begin::Close-->
                    <div id="{{ $modal_id }}_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>

                <!--begin::Modal body-->
                <div class="modal-body py-10 px-lg-17">
                    <!--begin::Scroll-->
                    <div class="scroll-y me-n7 pe-7" id="{{ $modal_id }}_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#{{ $modal_id }}_header" data-kt-scroll-wrappers="#{{ $modal_id }}_scroll" data-kt-scroll-offset="300px">
                        <input type="hidden" name="_id">
                        <input type="hidden" name="_method">
                        @yield('form_modal')	
                    </div>
                </div>
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <button type="reset" id="{{ $modal_id }}_cancel" class="btn btn-light me-3">{{ __('main.reset_form') }}</button>
                    <button type="submit" id="{{ $modal_id }}_submit" class="btn btn-primary">
                        <span class="indicator-label" id="{{ $modal_id }}_submit_text"></span>
                        <span class="indicator-progress">{{ __('main.please_wait') }}...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('private_js')
    <script>
        var KTModalDefault = function () {
            var cancelButton;
            var closeButton;
            var form;
        
            // Init form inputs
            var handleModal = function () {
                cancelButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    form.reset(); // Reset form	

                    var selects = form.querySelectorAll('select');
                    if (selects !== null && selects !== undefined) {
                        selects.forEach(select => {
                            $(select).val('').trigger('change');
                        });
                    }
                });
                
                closeButton.addEventListener('click', function(e){
                    e.preventDefault();
                    form.reset(); // Reset form	

                    var selects = form.querySelectorAll('select');
                    if (selects !== null && selects !== undefined) {
                        selects.forEach(select => {
                            $(select).val('').trigger('change');
                        });
                    }
                                        
                    $('#{{ $modal_id }}_trigger').modal('hide');
                })
            }

            return {
                // Public functions
                init: function () {
                    // Elements
                    form = document.querySelector('#{{ $modal_id }}');
                    cancelButton = form.querySelector('#{{ $modal_id }}_cancel');
                    closeButton = form.querySelector('#{{ $modal_id }}_close');
                    handleModal();
                }
            };
        }();

        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            KTModalDefault.init();
        });
    </script>
@endpush