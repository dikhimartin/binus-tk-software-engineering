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
					order: [[2, 'desc']],
					stateSave: true,
					select: {
						style: 'multi',
						selector: 'td:first-child input[type="checkbox"]',
						className: 'row-selected'
					},
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
								return '';
							},
						},
					],
					// Add data-filter attribute
					createdRow: function (row, data, dataIndex) {
						$(row).find('td:eq(4)').attr('data-filter', data.CreditCardType);
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