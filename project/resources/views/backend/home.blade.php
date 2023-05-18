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
                    var action = `onclick="add_to_cart(this)"`;

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