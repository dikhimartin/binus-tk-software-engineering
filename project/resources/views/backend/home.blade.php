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
            </div>
        </div>
    </div>

    <!--begin::Modal Component-->
    @component('backend.components.modal', ['modal_size' => 'modal-lg', 'is_header' => false, 'modal_id' => 'modal_book_room'])
        @section('modal_content')
            <div class="mb-5 text-center">
                <h1 class="mb-2" id="room_name"></h1>
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
                            <div class="text-primary text-end fw-bold fs-1" id="room_price"></div>
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

                <div class="separator my-10"></div>


            </div>
        @endsection
        @section('modal_footer')
            <a href="javascript:void(0)" class="btn btn-primary">Pesan Sekarang</a>
        @endsection        
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
        });

        // define throttling function
        function throttle(func, delay) {
            let timeoutId;
            return function(...args) {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => func.apply(this, args), delay);
            };
        }

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
                    var action = `onclick="book_room(this)"`;

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

        function book_room(element){
            // Get data
            var room_id = element.id;

			// Fetching data
			$.ajax({
				url : "{{ url('admin/rooms') }}" + '/' + room_id,
				type: "GET",
				dataType: "JSON",             
				success: function(response){
					if (response.status.code === 200) {
						const data = response.data;
                        $("#room_name").text(data.name);
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
            var formData = new FormData(document.querySelector('#form_cart_order'));
            $.ajax({
                url : "/api/transaction/order",
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
                    clear_item();
                    getRoomList();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.responseJSON.status.message != undefined){
                        errorThrown = jqXHR.responseJSON.status.message;
                    }
                    ToastrError(errorThrown);
                }
            }); 
        }	        

    </script>
@endpush