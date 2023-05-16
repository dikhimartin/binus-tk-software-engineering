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
                            <input type="text" id="search-query" class="form-control" placeholder="{{__('main.search')}} {{__('main.product')}}">
                        </div>
                        <div id="product-list-container" class="row"></div>
                    </div>
                </div>
                
                <!--begin::Sidebar-->
                <div class="flex-row-auto w-xl-550px">
                    <form class="form" id="form_cart_order" enctype="multipart/form-data">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="card card-flush bg-body">
                            <div class="card-header pt-5">
                                <h3 class="card-title fw-bold text-gray-800 fs-2qx">Daftar keranjang</h3>
                                <div class="card-toolbar">
                                    <a href="javascript:void(0)" onclick="clear_cart()" class="btn btn-light-primary fs-4 fw-bold py-4">Hapus keranjang</a>
                                </div>
                            </div>
                            <div class="card-body pt-0">
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
                                
                                <div class="d-flex flex-stack bg-success rounded-3 p-6 mb-11">
                                    <div class="fs-6 fw-bold text-white">
                                        <span class="d-block fs-2qx lh-1">Total</span>
                                    </div>
                                    <div class="fs-6 fw-bold text-white text-end">
                                        <span class="d-block fs-2qx lh-1" id="grant-total"></span>
                                    </div>
                                </div>
                                <div class="m-0">
                                    <a href="javascript:void(0)" onclick="SendOrder()" class="btn btn-primary fs-1 w-100 py-4">Pesan</a>
                                </div>
                            </div>
                        </div>
                    </form>
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
            getProductList();
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
        const getProductList = () => {
            axios.get('/api/products', {
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
                $('#product-list-container').empty();
                if(response.data.data.length === 0) {
                    $('#product-list-container').html('<p>No products found.</p>');
                }

                // loop through the product data and add new cards to the container
                response.data.data.forEach(product => {
                    var action = `onclick="add_to_cart(this)"`;
                    if (product.stock <= 0){
                        action = `onclick="ToastrError('Stok habis')"`;
                    }

                    var path = `{{ config('app.url') }}`;
                    var product_assets = path + '/images/product.png';
                    if (product.assets_relative_path != null){
                        product_assets = path + '/' + product.assets_relative_path; 
                    }

                    var formatted_price = IDRCurrency(product.selling_price);
                    let cardHtml = `
                        <div class="col-md-3 mb-4">
                            <a href="javascript:void(0)" class="text-black">
                                <div ${action} class="card card-product" id="${product.id}">
                                    <img class="card-img-top" src="${product_assets}" alt="${product.name}"  width="200" height="200">
                                    <div class="card-body card-hightlight" id="hightlight_id_${product.id}">
                                        <h5 class="card-title text-center product-name">${product.name}</h5>
                                        <p class="card-text product text-center text-grey fw-semibold d-block fs-6 mt-n1">${product.product_type_name}</p>
                                        <p class="card-text product-price text-center text-end fw-bold fs-1" price="${product.selling_price}" >${formatted_price}</p>
                                        <p class="card-text product-stock text-center fw-bold">${product.stock}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    `;
                    $('#product-list-container').append(cardHtml);
                });
            })
            .catch(error => {
                $('#product-list-container').html('<p>Product not available.</p>');
            });
        };

        // define function to handle search query input
        const handleSearchQuery = () => {
            searchQuery = $('#search-query').val();
            getProductList();
        };

        // attach event listener to search query input
        $('#search-query').on('input', throttle(function(event) {
            const searchQuery = event.target.value.trim();
            if (searchQuery.length >= 3 || searchQuery.length === 0) {
                setTimeout(() => handleSearchQuery(searchQuery), 100);
            }
        }, 100));

        function add_to_cart(element){
            // Get data
            var product_id = element.id;
            var product_image = element.children[0].src;
            var product_name = element.querySelector('.product-name').textContent;
            var product_price = element.querySelector('.product-price').getAttribute('price');
            var formated_price = element.querySelector('.product-price').textContent;
            var product_stock = element.querySelector('.product-stock').textContent;            

            const existingItem = $(`#item-cart tr[data-id="${product_id}"]`);

             // If the product is already in the cart, update the quantity
            if (existingItem.length) {
                return
            }

            let item = `
                <tr data-id="${product_id}">
                    <input type="hidden" name="product_id[]" value="${product_id}">
                    <input  id="product_price_${product_id}" type="hidden" name="product_price[]" value="${parseInt(product_price)}">
                    <td class="pe-0">
                        <div class="d-flex align-items-center">
                        <img src="${product_image}" class="w-50px h-50px rounded-3 me-3" alt="">
                        <span class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-6 me-1">${product_name}</span>
                        </div>
                    </td>
                    <td class="pe-0">
                        <div class="input-qty input-group input-group-sm">
                            <button class="btn btn-sm btn-outline-secondary" onclick="decreased_qty('${product_id}')" type="button" data-quantity="minus" data-field="quantity">
                                <i class="fa fa-duotone fa-minus"></i>
                            </button>
                            <input type="text" id="quantity_${product_id}" onkeyup="input_qty('${product_id}')" name="quantity[]" class="form-control input-number text-center" value="1" min="1" max="${product_stock}">
                            <button class="btn btn-sm btn-outline-secondary" onclick="increased_qty('${product_id}')" type="button" data-quantity="plus" data-field="quantity">
                                <i class="fa fa-duotone fa-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td class="text-end">
                        <span class="fw-bold text-primary fs-2" id="sub_price_${product_id}" data-sub-price="${product_price}" >${formated_price}</span>
                    </td>
                    <td class="text-end">
                        <a href="javascript:void(0)" onclick=remove_item('${product_id}') class="btn btn-sm btn-danger">x
                        </a>
                    </td>
                </tr>
            `;
            // Add highlight to the corresponding div
            $(`#hightlight_id_${product_id}`).addClass('bg-primary text-white');

            $("#item-cart").append(item);
            accumulate_grand_total();
        }

        function clear_cart(){
            Swal.fire({
                text: "{{ __('main.are_you_sure_want_to_delete' )}}",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "{{ __('main.yes_deleted') }}",
                cancelButtonText: "{{ __('main.no_cancel') }}",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function (result) {
                if (result.value) {
                    clear_item();
                } else if (result.dismiss === 'cancel') {
                    SwalError("{{ __('main.was_not_deleted') }}");
                }
            });
        }

        function remove_item(id){
            const item = $(`#item-cart tr[data-id="${id}"]`);
            item.remove(); // removes the selected item from the cart
            $(`#hightlight_id_${id}`).removeClass('bg-primary text-white');
            accumulate_grand_total();
        }

        function clear_item(){
            const item = $(`#item-cart`);
            item.empty();
            $('.card-hightlight').removeClass('bg-primary text-white');
            accumulate_grand_total();
        }

        function increased_qty(id){
            var $input = $("#quantity_"+id);
            var quantity = parseInt($input.val());
            var quantityMax = parseInt($input.attr('max'));

            if (quantity < quantityMax) {
                $input.val(quantity + 1).trigger('change');
            }
            sub_total(id);
        }
        
        function decreased_qty(id){
            var $input = $("#quantity_"+id);
            var quantity = parseInt($input.val());
            var quantityMin = parseInt($input.attr('min'));
    
            if (quantity > quantityMin) {
                $input.val(quantity - 1).trigger('change');
            }
            sub_total(id);
        }

        function input_qty(id){
            var $input = $("#quantity_"+id);
            var quantity = parseInt($input.val());
            var quantityMin = parseInt($input.attr('min'));
            var quantityMax = parseInt($input.attr('max'));

            // check if input is a valid number
            if (isNaN(quantity)) {
                quantity = quantityMin;
            }

            // limit input value to min and max values
            if (quantity < quantityMin) {
                quantity = quantityMin;
            }
            if (quantity > quantityMax) {
                quantity = quantityMax;
            }

            $input.val(quantity);
            sub_total(id);
        }

        function sub_total(id){
            var $element = $("#sub_price_"+id);
            var quantity = parseInt($("#quantity_"+id).val());
            var pricePerProduct = parseInt($("#product_price_"+id).val());
            var sub_total = (quantity *  pricePerProduct);

            $element.attr('data-sub-price', sub_total);
            $element.text(IDRCurrency(sub_total));
            accumulate_grand_total();
        }

        function accumulate_grand_total(){
            let totalPrice = 0;
            let subPrices = document.querySelectorAll('#item-cart [data-sub-price]');
            subPrices.forEach(function(subPrice) {
                totalPrice += parseInt(subPrice.dataset.subPrice);
            });
            $("#grant-total").text(IDRCurrency(totalPrice));
        }

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
                    getProductList();
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