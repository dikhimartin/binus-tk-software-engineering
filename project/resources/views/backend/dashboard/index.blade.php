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
                        <div class="input-group mb-8">
                            <input type="text" id="search-query" class="form-control" placeholder="{{__('main.search')}} {{__('main.room')}}">
                        </div>
                        <div id="room-list-container" class="row"></div>
                    </div>
                </div>

                <!--begin::Sidebar-->
                <div class="flex-row-auto w-xl-550px">
                    <form class="form" id="form_reservation_order" enctype="multipart/form-data">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="card card-flush bg-body">
                            <div class="card-header pt-5">
                                <h3 class="card-title fw-bold text-gray-800 fs-2qx">Pemesanan Kamar</h3>
                                <div class="card-toolbar">
                                    <a href="javascript:void(0)" id="button_cancel" onclick="cancel_book_room()" class="btn btn-sm btn-light-danger fs-4" style="display:none;">Batalkan Pesanan</a>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="separator my-5"></div>
                                <!-- Hotel information -->
                                <div id="hotel_information"></div>

                                <!-- input data -->
                                <div id="input_data" style="display:none">
                                    <!-- Check-in and Check-out -->
                                    <div class="row mb-5">
                                        <div class="col-sm-6" id="kt_td_picker_linked_1" >
                                            <label for="kt_td_picker_linked_1_input" class="form-label required">{{ __('main.check_in') }}</label>
                                            <div class="input-group log-event" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                                <input name="check_in_date" placeholder="{{ __('main.date') }} {{ __('main.check_in') }}" id="kt_td_picker_linked_1_input" type="text" class="form-control" data-td-target="#kt_td_picker_linked_1"/>
                                                <span class="input-group-text" data-td-target="#kt_td_picker_linked_1" data-td-toggle="datetimepicker">
                                                    <i class="fa fa-duotone fa-calendar"></i>  
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" id="kt_td_picker_linked_2" >
                                            <label for="kt_td_picker_linked_2_input" class="form-label required">{{ __('main.check_out') }}</label>
                                            <div class="input-group log-event" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                                <input name="check_out_date" placeholder="{{ __('main.date') }} {{ __('main.check_out') }}" id="kt_td_picker_linked_2_input" type="text" class="form-control" data-td-target="#kt_td_picker_linked_2"/>
                                                <span class="input-group-text" data-td-target="#kt_td_picker_linked_2" data-td-toggle="datetimepicker">
                                                    <i class="fa fa-duotone fa-calendar"></i>  
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Extra Charge -->
                                    <div class="text-muted fw-semibold">
                                        Punya permintaan khusus? Ajukan permintaan Anda dan properti akan berusaha memenuhinya. 
                                        (Permintaan khusus tidak dijamin dan dapat dikenakan biaya) 
                                    </div>
                                    <div class="fw-semibold fs-5 mt-5">
                                        <a href="javascript:void(0)" onclick="select_extra_charge()" class="btn btn-sm btn-primary">Pilh Extra Charge</a>
                                    </div>
                                    <div class="table-responsive mb-8">
                                        <table class="table align-middle gs-0 gy-4 my-0">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th class="min-w-125px"></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="item-cart"></tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="d-flex flex-stack bg-success rounded-3 p-6 mb-11">
                                    <div class="fs-6 fw-bold text-white">
                                        <span class="d-block fs-2qx lh-1">Total</span>
                                    </div>
                                    <div class="fs-6 fw-bold text-white text-end">
                                        <span class="d-block fs-2qx lh-1" id="grant-total"></span>
                                    </div>
                                </div>
                                <div class="m-0">
                                    <a href="javascript:void(0)" onclick="SendOrder()" class="btn btn-primary fs-1 w-100 py-4">Pesan Sekarang</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>        
            </div>
        </div>

    </div>

    <!--begin::Modal Room Review-->
    @component('backend.components.modal', ['modal_size' => 'modal-lg', 'is_header' => false, 'modal_id' => 'modal_book_room'])
        @slot('modal_content')
            <div id="book_preview" room-id="it-dynamic-value">
                <div class="mb-5 text-center">
                    <h1 class="mb-2" id="room_name"></h1>
                    <h2 class="mb-2 text-muted mb-5 fw-semibold" id="room_type"></h2>
                    <div class="separator my-10"></div>
                    <img id="preview_room_asset" 
                        class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded mt-1" 
                        height="300" width="100%" 
                        src="http://localhost:8000/storage/room/P-1684384790-J4DNCnJ1UB.webp">
                </div>
                <div class="card-body">
                    <!--begin::Info Kamar-->
                    <div class="mb-13">
                        <h4 class="text-gray-700 w-bolder mb-0">Harga</h4>
                        <div class="my-2">
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-primary text-end fw-bold fs-1" id="room_price" value="0"></div>
                            </div>
                        </div>
                    </div>

                    <!--begin::Info Kamar-->
                    <div class="mb-8">
                        <h4 class="text-gray-700 w-bolder mb-0">Info Kamar</h4>
                        <div class="my-2">
                            <div class="d-flex align-items-center mb-3">
                                <span class="bullet bg-primary me-3"></span>
                                <div class="text-gray-600 fw-semibold fs-6" id="room_area"></div>
                            </div>
                        </div>
                    </div>

                    <!--begin::Fasilitas Kamar-->
                    <div class="mb-8">
                        <h4 class="text-gray-700 w-bolder mb-0">Fasilitas Kamar</h4>
                        <div class="my-2" id="facilities-list"></div>
                    </div>

                    <!--begin::Description -->
                    <div class="mb-7">
                        <h4 class="text-gray-700 w-bolder mb-3">Deskripsi Kamar</h4>
                        <div class="fw-semibold fs-6 text-gray-600" id="room_description"></div>
                    </div>
                </div>
            </div>
        @endslot
        @slot('modal_footer')
            <a href="javascript:void(0)" id="button_book_room" onclick="book_room(this)" class="btn btn-primary">Lanjutkan Pemesanan</a>
        @endslot
    @endcomponent

    
    <!--begin::Modal Extra Charge-->
    @component('backend.components.modal', ['modal_size' => 'modal-lg', 'is_header' => true, 'modal_id' => 'modal_extra_charge'])
        @slot('modal_content')
            @include('backend.dashboard.shared.extra-charge')
        @endslot
    @endcomponent

@endsection

<!--begin::Vendors Javascript(used for this page only)-->
@push('private_js')
    <script>
        // define variables for search and filter parameters
        let searchQuery = '';
        let filterType = '';

        $(document).ready(function() {
            getRoomList();
            KTDatePickerLinked();
        });

        // define function to get product list from API
        const getRoomList = () => {
            axios.get('/api/rooms', {
                params: {
                    draw: 1,
                    start: 0,
                    length: 10,
                    'search[value]': searchQuery,
                    filter: filterType
                },
            })
            .then(response => {
                // remove previous product cards from the container
                $('#room-list-container').empty();
                if(response.data.data.length === 0) {
                    $('#room-list-container').html('<p>kamar tidak dapat ditemukan.</p>');
                }

                // loop through the item data and add new cards to the container
                response.data.data.forEach(item => {
                    var action = `onclick="review_room(this)"`;

                    var path = `{{ config('app.url') }}`;
                    var room_assets = path + '/images/product.png';
                    if (item.assets_relative_path != null){
                        room_assets = path + '/' + item.assets_relative_path; 
                    }

                    var formatted_price = IDRCurrency(item.price);
                    let cardHtml = `
                        <div class="col-md-3 mb-4">
                            <a href="javascript:void(0)" class="text-black">
                                <div ${action} class="card card-product" id="${item.id}">
                                    <img class="card-img-top" src="${room_assets}" alt="${item.name}"  width="200" height="200">
                                    <div class="card-body card-hightlight" id="hightlight_id_${item.id}">
                                        <h5 class="card-title text-center product-name">${item.name}</h5>
                                        <p class="card-text product text-center text-grey fw-semibold d-block fs-6 mt-n1">${item.room_type_name}</p>
                                        <p class="card-text product-price text-center text-end fw-bold fs-1" price="${item.price}" >${formatted_price}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    `;
                    $('#room-list-container').append(cardHtml);
                });
            })
            .catch(error => {
                $('#room-list-container').html('<p>kamar tidak dapat ditemukan.</p>');
            });
        };

        function review_room(element){
            // Get data
            var room_id = element.id;

			// Fetching data
			$.ajax({
				url : "{{ url('api/rooms') }}" + '/' + room_id,
				type: "GET",
				dataType: "JSON",             
				success: function(response){
                    var divElement = document.getElementById('book_preview');
                    var buttonElement = document.getElementById('button_book_room');
                    
					if (response.status.code === 200) {
                        const data = response.data;

                        divElement.setAttribute('room-id', data.id);
                        divElement.querySelector('#room_price').setAttribute('value', data.price);
                        
                        buttonElement.setAttribute('onclick', "book_room('" + data.id + "')");

                        $("#room_name").text(data.name);
                        $("#room_type").text(data.room_type_name);
                        $("#room_price").text(IDRCurrency(data.price));
                        $("#room_area").text(`${data.area} m2`);
                        $("#room_description").html(data.description);

                        var facilities = data.facilities;
                        var facilitiesList = document.getElementById('facilities-list');

                        // Empty the existing list
                        facilitiesList.innerHTML = '';

                        if (facilities.length > 0) {
                            for (var i = 0; i < facilities.length; i++) {
                                var facility = facilities[i];
                                var listItem = document.createElement('div');
                                listItem.className = 'd-flex align-items-center mb-3';
                                listItem.innerHTML = `
                                    <span class="bullet bg-primary me-3"></span>
                                    <div class="text-gray-600 font-semibold text-sm">${facility.name}</div>
                                `;
                                facilitiesList.appendChild(listItem);
                            }
                        } else {
                            facilitiesList.innerHTML = '<p>Tidak ada fasilitas yang tersedia.</p>';
                        }



						var path = `{{ config('app.url') }}`;
						var room_assets = path + '/images/product.png';
						if (data.assets_relative_path != null){
							room_assets = path + '/' + data.assets_relative_path; 
							$("#preview_room_asset").show();
						}						
						$("#preview_room_asset").attr('src', room_assets);

						// Show the modal
                        $('#modal_book_room').modal('show');	
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					if (jqXHR.responseJSON.status.message != undefined){
						errorThrown = jqXHR.responseJSON.status.message;
					}
					ToastrError(errorThrown);
				}
			});	
        }

        function book_room(id){
            var element = document.querySelector('[room-id="' + id + '"]');

            var roomName = element.querySelector('#room_name').textContent;
            var roomType = element.querySelector('#room_type').textContent;
            var roomPrice = element.querySelector('#room_price').textContent;
            var roomPriceVal = element.querySelector('#room_price').getAttribute('value');
            var previewRoomAsset = element.querySelector('#preview_room_asset').getAttribute('src');
            var roomDescription = element.querySelector('#room_description').textContent;

            var html = `
                <h2 class="mb-0">${roomName}</h2>
                <input type="hidden" name="room_id" value="${id}">
                <input type="hidden" name="room_price" value="${roomPriceVal}">
                <p class="text-muted mb-5 fw-semibold">${roomType}</p>
                <img class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded mb-6" height="200" width="100%" src="${previewRoomAsset}">
                <div class="mb-10">
                <h4 class="text-gray-700 w-bolder mb-0">Harga</h4>
                <div class="my-2">
                    <div class="d-flex align-items-center mb-3">
                    <div class="text-primary text-end fw-bold fs-1">${roomPrice}</div>
                    </div>
                </div>
                </div>
                <div class="separator my-5"></div>
            `;

            var hotelInformation = document.getElementById('hotel_information');
            hotelInformation.innerHTML = html;   

            // console.log(element);
            $('#modal_book_room').modal('hide');	
            $('#input_data').show();
            $('#button_cancel').show();
            accumulate_grand_total();
            clear_item();
        }
        
        function cancel_book_room(){
            var hotelInformation = document.getElementById('hotel_information');
            hotelInformation.innerHTML = '';   
            $('#input_data').hide();
            $('#button_cancel').hide();
            clear_item();
            ToastrWarning("Pemesanaan dibatalkan");
        }

        function select_extra_charge(){
            $("#modal_extra_charge_header_title").text("{{ __('main.please_choose') }} Extra Charge")
            $('#modal_extra_charge').modal('show');	
        }

        function accumulate_grand_total(){
            let totalPrice = 0;
            let subPrices = document.querySelectorAll('#item-cart [data-sub-price]');
            let roomPrice =  parseInt($('input[name="room_price"]').val());
            subPrices.forEach(function(subPrice) {
                totalPrice += parseInt(subPrice.dataset.subPrice);
            });
            totalPrice += roomPrice;
            $("#grant-total").text(IDRCurrency(totalPrice));
        }

        // define function to handle search query input
        const handleSearchQuery = () => {
            searchQuery = $('#search-query').val();
            getRoomList();
        };

        // attach event listener to search query input
        $('#search-query').on('input', throttle(function(event) {
            const searchQuery = event.target.value.trim();
            if (searchQuery.length >= 3 || searchQuery.length === 0) {
                setTimeout(() => handleSearchQuery(searchQuery), 100);
            }
        }, 100));

        // Send data to server
        function SendOrder() {
            var formData = new FormData(document.querySelector('#form_reservation_order'));
            $.ajax({
                url : "/api/transaction/reservation",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },						
                dataType: "JSON",
                success: function(response){
                    var messages = "Pembelian berhasil";
                    ToastrSuccess(messages);
                    // clear_item();
                    // getRoomList();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    let errorMessage = "An error occurred.";
                    if (jqXHR.responseJSON && jqXHR.responseJSON.status && jqXHR.responseJSON.status.message) {
                        const errorMessages = jqXHR.responseJSON.status.message;

                        // Construct the error message dynamically
                        const errorFields = Object.keys(errorMessages);
                        if (errorFields.length > 0) {
                            errorMessage = "The following errors occurred: \n";
                            
                            errorFields.forEach((field) => {
                                errorMessage += `${errorMessages[field]}\n`;
                            });
                        }
                    }
                    ToastrError(errorMessage);
                }
            }); 
        }	        

    </script>
@endpush