<!--begin::Datatable-->
<div class="container">
    @component('backend.components.datatable-header')
    @endcomponent
	<div class="row">
        <table id="kt_datatable_server_side" class="table align-middle table-row-dashed fs-6 gy-5">
            <thead>
            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                <th>{{ __('main.name') }}</th>
                <th>{{ __('main.price') }}</th>
                <th class="text-end min-w-100px">{{__('main.action')}}</th>
            </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
            </tbody>
        </table>
    </div>
</div>

<!--begin::Vendors Javascript(used for this page only)-->
@push('private_js')
	<script src="{{ URL::asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
	<script>
		"use strict";

		const URL_API = `{{ url('admin/extra-charges') }}`

        function add_extra_charge_to_cart(element){
            var extra_charge_id  = element.getAttribute('data-id');
            var name  = element.getAttribute('data-name');
            var price = element.getAttribute('data-price');
            var formated_price = element.getAttribute('data-price-format');

            const existingItem = $(`#item-cart tr[data-id="${extra_charge_id}"]`);

            // If the items is already in the cart, update the quantity
            if (existingItem.length) {
                ToastrError("Item sudah terdaftar di dalam list");
                return
            }

            let item = `
                <tr data-id="${extra_charge_id}">
                    <input type="hidden" name="extra_charge_id[]" value="${extra_charge_id}">
                    <input  id="extra_charge_price_${extra_charge_id}" type="hidden" name="price[]" value="${parseInt(price)}">
                    <td class="pe-0">
                        <div class="d-flex align-items-center">
                            <span class="fw-bold text-gray-800 cursor-pointer text-hover-primary fs-6 me-1">${name}</span>
                        </div>
                    </td>
                    <td class="pe-0">
                        <div class="input-qty input-group input-group-sm">
                            <button class="btn btn-sm btn-outline-secondary" onclick="decreased_qty('${extra_charge_id}')" type="button" data-quantity="minus" data-field="quantity">
                                <i class="fa fa-duotone fa-minus"></i>
                            </button>
                            <input type="text" id="quantity_${extra_charge_id}" onkeyup="input_qty('${extra_charge_id}')" name="quantity[]" class="form-control input-number text-center" value="1" min="1" max="100">
                            <button class="btn btn-sm btn-outline-secondary" onclick="increased_qty('${extra_charge_id}')" type="button" data-quantity="plus" data-field="quantity">
                                <i class="fa fa-duotone fa-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td class="text-end">
                        <span class="fw-bold text-primary fs-2" id="sub_price_${extra_charge_id}" data-sub-price="${price}" >${formated_price}</span>
                    </td>
                    <td class="text-end">
                        <a href="javascript:void(0)" onclick=remove_item('${extra_charge_id}') class="btn btn-sm btn-danger">x
                        </a>
                    </td>
                </tr>
            `;
            // Add highlight to the corresponding div
            // $(`#hightlight_extra_charge_id_${extra_charge_id}`).addClass('bg-primary text-white');

            $("#item-cart").append(item);
            accumulate_grand_total();
        }

        function remove_item(id){
            const item = $(`#item-cart tr[data-id="${id}"]`);
            item.remove(); // removes the selected item from the cart
            accumulate_grand_total();
        }

        function clear_item(){
            const item = $(`#item-cart`);
            item.empty();
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
            var pricePerProduct = parseInt($("#extra_charge_price_"+id).val());
            var sub_total = (quantity *  pricePerProduct);

            $element.attr('data-sub-price', sub_total);
            $element.text(IDRCurrency(sub_total));
            accumulate_grand_total();
        }        

		// Class definition
		var KTDatatablesServerSide = function () {
			// Shared variables
			var table;
			var dt;

			// Private functions
			var initDatatable = function () {
				dt = $("#kt_datatable_server_side").DataTable({
					searchDelay: 500,
					processing: true,
					serverSide: true,
					order: [[0, 'desc']],
					stateSave: true,
					ajax: {
						url: URL_API,
					},
					columns: [
						{ data: 'name' },
						{ data: 'price' },
						{ data: "id" },
					],
					columnDefs: [
						{
							targets: 1,
							render: function (data, type, row) {
								return IDRCurrency(row.price);
							}
						},
						{
							targets: 2,
							data: null,
							orderable: false,
							className: 'text-end',
							render: function (data, type, row) {
								return `<a href="javascript:void(0)" 
                                            data-id="${row.id}"
                                            data-name="${row.name}"
                                            data-price="${row.price}"
                                            data-price-format="${IDRCurrency(row.price)}"
                                            onclick="add_extra_charge_to_cart(this)" 
                                            class="btn btn-sm btn-success">+
                                        </a>`;
							},
						},
					],
                    rowCallback: function(row, data) {
                        // $(row).attr("id", "hightlight_extra_charge_id_" + data.id); // Add ID to the row
                    }
				});

				table = dt.$;

				// Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
				dt.on('draw', function () {
					KTMenu.createInstances();
				});
			}

			// Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
			var handleSearchDatatable = function () {
				const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
				filterSearch.addEventListener('keyup', function (e) {
					dt.search(e.target.value).draw();
				});
			}

			// Public methods
			return {
				init: function () {
					initDatatable();
					handleSearchDatatable();
				},
				refresh: function () {
					dt.draw();
				}				
			}
		}();

		// On document ready
		KTUtil.onDOMContentLoaded(function () {
            KTDatatablesServerSide.init();
		});
	</script>
@endpush