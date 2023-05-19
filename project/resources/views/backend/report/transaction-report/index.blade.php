@extends('layouts.backend.app')
@section('sidebarActive', $controller)

<!--begin::Vendor Stylesheets(used for this page only)-->
@push('private_css')
	<link href="{{ URL::asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
	<style>
		.td-class{
			vertical-align:top !important;
		}
	</style>
@endpush

@section('content')
    <!--begin::Toolbar Component-->
	@component('backend.components.toolbar', ['pages_title' => $pages_title, 'sub_menu' => null, 'sub_menu_link' => null])
		@slot('filter_slot')
			<!--begin::Menu 1-->
			<div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="kt-toolbar-filter">
				<!--begin::Header-->
				<div class="px-7 py-5">
					<div class="fs-4 text-dark fw-bold">{{ __('main.filter_options')}}</div>
				</div>
				<!--begin::Separator-->
				<div class="separator border-gray-200"></div>
				<!--begin::Content-->
				<div class="px-7 py-5" data-table-filter="form">
					<!--begin::Range date-->
					<div class="mb-10">
						<label class="form-label fs-5 fw-semibold mb-3">{{ __('main.room-type') }}:</label>
						<div data-table-filter="room_type">
							<select id="room_type" name="room_type_id" class="form-select form-select-solid" data-kt-select2="true" data-placeholder="{{ __('main.all') }}" data-allow-clear="true">
							<option value="">{{ __('main.all') }}</option>
								@foreach($room_types as $value)
									<option value="{{ $value->id }}">{{ $value->name }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<!--begin::Range date-->
					<div class="mb-10">
						<label class="form-label fs-5 fw-semibold mb-3">{{ __('main.transaction_date') }}:</label>
						<div class="input-group">
							<input class="form-control form-control-solid rounded rounded-end-0" placeholder="Rentang tanggal" id="kt_ecommerce_sales_flatpickr" />
							<button class="btn btn-icon btn-light" id="kt_ecommerce_sales_flatpickr_clear">
								<span class="svg-icon svg-icon-2">
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect opacity="0.5" x="7.05025" y="15.5356" width="12" height="2" rx="1" transform="rotate(-45 7.05025 15.5356)" fill="currentColor" />
										<rect x="8.46447" y="7.05029" width="12" height="2" rx="1" transform="rotate(45 8.46447 7.05029)" fill="currentColor" />
									</svg>
								</span>
							</button>
						</div>
					</div>
					<div class="d-flex justify-content-end">
						<button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true" data-kt-docs-table-filter="reset">{{ __('main.reset') }}</button>
						<button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true" data-kt-docs-table-filter="filter">{{ __('main.apply') }}</button>
					</div>
				</div>
			</div>
		@endslot
	@endcomponent	

	<!--begin::Card Grafik -->
	<div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-gray-800">Laporan Grafik</span>
					</h3>
				</div>
				<div class="card-body">
					<div id="bar-chart-transaction"></div>
				</div>
			</div>
		</div>
	</div>

	<!--begin::Card Table -->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
			<div class="card">
				<!--begin::Header Datatable Component -->
				@component('backend.components.datatable-header')
				@endcomponent
					
				<div class="card-body pt-0">
					<!--begin::Datatable-->
					<table id="kt_datatable_server_side" class="table align-middle table-row-dashed fs-6 gy-5">
						<thead>
						<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
							<th>{{ __('main.transaction_code') }}</th>
							<th>{{ __('main.room') }}</th>
							<th>{{ __('main.booker') }}</th>
							<th>{{ __('main.transaction_date') }}</th>
							<th>{{ __('main.total_transaction') }}</th>
							<th class="text-end min-w-100px">{{__('main.action')}}</th>
						</tr>
						</thead>
						<tbody class="text-gray-600 fw-semibold">
						</tbody>
					</table>
				</div>

				<!--begin::Modal Component-->
				@component('backend.components.modal', ['modal_size' => 'modal-lg', 'is_header' => true, 'modal_id' => $controller])
					@slot('modal_content')
						<div class="d-flex flex-column gap-5">
							<div class="table-responsive" id="transaction-detail"></div>
						</div>
					@endslot
				@endcomponent

			</div>
		</div>
	</div>
@endsection

<!--begin::Vendors Javascript(used for this page only)-->
@push('private_js')
	<script src="{{ URL::asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
	<script src="{{ URL::asset('assets/plugins/custom/highcharts/highcharts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/custom/highcharts/exporting.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/custom/highcharts/series-label.js') }}"></script>
	<script>
		"use strict";

		const URL_API = `{{ url('admin/report/transactions') }}`

		function show_transaction_detail(id){
			$('#{{ $controller  }}_header_title').text(`{{ __('main.transaction_detail') }}`);
			$.ajax({
				url : URL_API + '/' + id + "/detail",
				type: "GET",
				dataType: "JSON",             
				success: function(response){
					if (response.status.code === 200) {
						const data = response.data;

						// Generate table
						const table = $('<table>').addClass('table align-middle table-row-dashed fs-6 gy-5 mb-0');
						const thead = $('<thead>').appendTo(table);
						const tr = $('<tr>').addClass('text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0').appendTo(thead);
						$('<th>').text(`{{ __('main.extra-charge') }}`).appendTo(tr);
						$('<th>').text(`{{ __('main.price') }}`).appendTo(tr);
						$('<th>').text(`{{ __('main.quantity') }}`).appendTo(tr);
						$('<th>').text(`{{ __('main.sub_price') }}`).appendTo(tr);
						const tbody = $('<tbody>').appendTo(table);

						// Looping data transaction extra charges
						const transaction_extra_charge_list = data.transaction_extra_charges;
						if (transaction_extra_charge_list.length > 0){
							transaction_extra_charge_list.forEach(function(detail) {
								const tr = $('<tr>').appendTo(tbody);
								$('<td>').text(detail.extra_charge.name).appendTo(tr);
								$('<td>').text(IDRCurrency(detail.price)).appendTo(tr);
								$('<td>').text(detail.quantity).appendTo(tr);
								$('<td>').text(IDRCurrency(detail.sub_price)).appendTo(tr);
							});

							// Calculate total
							const totalQuantity = transaction_extra_charge_list.reduce(function (total, detail) {
								return total + detail.quantity;
							}, 0);
							const totalPrice = transaction_extra_charge_list.reduce(function (total, detail) {
								const subPrice = parseFloat(detail.sub_price.replace(/[^0-9.-]+/g, ''));
								return total + subPrice;
							}, 0);

							// Add total row
							const trTotal = $('<tr>').appendTo(tbody);
							$('<td>').text('Total').appendTo(trTotal);
							$('<td>').text('').appendTo(trTotal);
							$('<td>').text(totalQuantity).appendTo(trTotal);
							$('<td>').text(IDRCurrency(totalPrice)).appendTo(trTotal);
						} else {
							const tr = $('<tr>').appendTo(tbody);
							$('<td class="text-center text-muted">').attr('colspan', '4').text(`{{ __('main.no_data_found') }}`).appendTo(tr);
						}



						$('#transaction-detail').empty().append(table);
						$('#{{ $controller  }}').modal('show');	
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

		// Class definition
		var KTDatatablesServerSide = function () {
			// Shared variables
			var table;
			var dt;
			var flatpickr;
			var startDate, endDate;

			// Private functions
			var initDatatable = function () {
				dt = $("#kt_datatable_server_side").DataTable({
					searchDelay: 500,
					processing: true,
					serverSide: true,
					order: [[0, 'desc']],
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
						{data: 'sort'},
						{ 
							data: 'room_name',
							width: '20%' 
						},
						{ data: 'booker_name' },
						{ data: 'transaction_date' },
						{ data: 'final_total' },
						{ data: null },
					],
					columnDefs: [
						{	
							targets: 0,
							createdCell: function (td, cellData, rowData, row, col) {
								$(td).addClass('td-class');
							},
							render: function (data, type, row) {
								return `<a href="javascript:void(0)" onclick="show_transaction_detail('${row.id}')">${row.transaction_code}</a>`;
							}
						},
						{	
							targets: 1,
							createdCell: function (td, cellData, rowData, row, col) {
								$(td).addClass('d-flex align-items-center');
							},
							render: function (data, type, row) {
								var path = `{{ config('app.url') }}`;
								var room_assets = path + '/images/product.png';
								if (row.assets_relative_path != null){
									room_assets = path + '/' + row.assets_relative_path; 
								}

								var action = `onclick="show_transaction_detail('${row.id}')"`;
								var html = `<div class="symbol symbol-50px overflow-hidden me-3">
									<a href="javascript:void(0)" `+ action +`>
										<div class="symbol-label">
											<img src="`+ room_assets +`" alt="`+ row.room_name +`" class="w-100">
										</div>
									</a>
								</div>`;
									
								html += `<div class="d-flex flex-column">
											<a href="javascript:void(0)" `+ action +` class="text-gray-800 text-hover-primary mb-1">`+ row.room_name +`</a>
											<span>`+ row.room_type_name +`</span>
										</div>`;

								return html;
							}
						},
						{	
							targets: 2,
							render: function (data, type, row) {
								return `<a href="javascript:void(0)" class="text-dark fw-bold text-hover-primary d-block mb-1 fs-7">${data}</a>
										<span class="text-muted fw-semibold text-muted d-block fs-8">{{ __('main.check_in') }}: </span>
										<span class=" fw-semibold text-muted d-block fs-8 mb-2">${ convertDate(row.check_in_date) }</span>

										<span class="text-muted fw-semibold text-muted d-block fs-8">{{ __('main.check_out') }}: </span>
										<span class=" fw-semibold text-muted d-block fs-8 mb-2">${ convertDate(row.check_out_date) }</span>

										<span class="text-muted fw-semibold text-muted d-block fs-8">{{ __('main.number_of_days') }}: </span>
										<span class=" fw-semibold text-muted d-block fs-8">${ row.days } {{ __('main.days') }}</span>
								`;
							}
						},	
						{	
							targets: 3,
							createdCell: function (td, cellData, rowData, row, col) {
								$(td).addClass('td-class');
							},
							render: function (data, type, row) {
								return convertDateTime(data);
							}
						},
						{	
							targets: 4,
							createdCell: function (td, cellData, rowData, row, col) {
								$(td).addClass('td-class');
							},
							render: function (data, type, row) {
								var html = '';
								html += `<span  class="fw-bold text-orange d-block mb-1 fs-6">${IDRCurrency(data)}</span>`

								if(parseInt(row.total_room_price) != 0){
									html += `<span class="text-muted fw-semibold text-muted d-block fs-7">{{ __('main.total_room_price') }}: </span>`
									html += `<span class=" fw-semibold text-muted d-block fs-8 mb-2">${ IDRCurrency(row.total_room_price) }</span>`
								}

								if(parseInt(row.total_extra_charge) != 0){
									html += `<span class="text-muted fw-semibold text-muted d-block fs-7">{{ __('main.total_extra_charge') }}: </span>`
									html += `<span class=" fw-semibold text-muted d-block fs-8 mb-2">${ IDRCurrency(row.total_extra_charge) }</span>`
								}

								return html;
							}
						},
						{
							targets: -1,
							data: null,
							orderable: false,
							createdCell: function (td, cellData, rowData, row, col) {
								$(td).addClass('td-class text-end');
							},
							render: function (data, type, row) {
								return `
									<a href="javascript:void(0)" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
										{{__('main.action')}}
										<span class="svg-icon fs-5 m-0">
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<polygon points="0 0 24 0 24 24 0 24"></polygon>
													<path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
												</g>
											</svg>
										</span>
									</a>
									<!--begin::Menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
										<!--begin::Menu item-->

										@permission('transaction-list')
											<div class="menu-item px-3">
												<a href="javascript:void(0)" onclick="show_transaction_detail('${data["id"]}')" class="menu-link px-3" data-kt-docs-table-filter="edit_row">
													{{ __('main.show') }}
												</a>
											</div>
										@endpermission
									</div>
								`;
							},
						},
					],
				});

				table = dt.$;

				// Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
				dt.on('draw', function () {
					KTMenu.createInstances();
				});
			}

			// Init flatpickr --- more info: https://flatpickr.js.org/getting-started/
			var initFlatpickr = () => {
				const element = document.querySelector('#kt_ecommerce_sales_flatpickr');
				flatpickr = $(element).flatpickr({
					altInput: true,
					altFormat: "d/m/Y",
					dateFormat: "Y-m-d",
					mode: "range",
					onChange: function (selectedDates, dateStr, instance) {
						startDate = selectedDates[0] ? formatDateFilterRange(selectedDates[0]) : '';
						endDate = selectedDates[1] ? formatDateFilterRange(selectedDates[1]) : '';
					}
				});
			}

			var clearFlatPickr = () =>{
				startDate = "";
				endDate = "";
				flatpickr.clear();
			}

			// Handle clear flatpickr
			var handleClearFlatpickr = () => {
				const clearButton = document.querySelector('#kt_ecommerce_sales_flatpickr_clear');
				clearButton.addEventListener('click', e => {
					clearFlatPickr()
				});
			}

			// Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
			var handleSearchDatatable = function () {
				const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
				filterSearch.addEventListener('keyup', function (e) {
					dt.search(e.target.value).draw();
				});
			}

			// generateFilter 
			var generateFilter = () => {
				const filters = [
					{ name: "room_type_id", selector: '[data-table-filter="room_type"] select[name="room_type_id"]' },
					{ name: "start_date", value: startDate },
					{ name: "end_date", value: endDate },
				];
				const queryParams = filters.map((filter) => {
					var	value = document.querySelector(filter.selector)?.value ?? "";
					if (filter.value != undefined && value == ""){
						value = filter.value;
					}
					return `${filter.name}=${value}`;
				});
				return `?${queryParams.join("&")}`;
			}

			// Filter Datatable
			var handleFilterDatatable = () => {
				// Select filter options
				const filterButton = document.querySelector('[data-kt-docs-table-filter="filter"]');
				// Filter datatable on submit
				filterButton.addEventListener('click', function () {
					dt.ajax.url(URL_API + generateFilter()).load();
					barChartReport();
				});
			}

			// Reset Filter
			var handleResetForm = () => {
				// Select reset button
				const resetButton = document.querySelector('[data-kt-docs-table-filter="reset"]');
				// Reset datatable
				resetButton.addEventListener('click', function () {
					clearFlatPickr()

					// Select filter options
					const filterForm = document.querySelector('[data-table-filter="form"]');
					const selectOptions = filterForm.querySelectorAll('select');

					// Reset select2 values -- more info: https://select2.org/programmatic-control/add-select-clear-items
					selectOptions.forEach(select => {
						$(select).val('').trigger('change');
					});

					dt.ajax.url(URL_API).load();
					barChartReport();
				});
			}

			// Public methods
			return {
				init: function () {
					initDatatable();
					initFlatpickr();
					handleSearchDatatable();
					handleFilterDatatable();
					handleClearFlatpickr();
					handleResetForm();
				},
				refresh: function () {
					dt.draw();
				}				
			}
		}();

		// Bar Chart
		var barChartReport = () => {
			Highcharts.chart('bar-chart-transaction', {
				chart: {
					type: 'column'
				},
				title: {
					text: 'Monthly Average Rainfall'
				},
				subtitle: {
					text: 'Source: WorldClimate.com'
				},
				xAxis: {
					categories: [
						'Jan',
						'Feb',
						'Mar',
						'Apr',
						'May',
						'Jun',
						'Jul',
						'Aug',
						'Sep',
						'Oct',
						'Nov',
						'Dec'
					],
					crosshair: true
				},
				yAxis: {
					min: 0,
					title: {
						text: 'Rainfall (mm)'
					}
				},
				tooltip: {
					headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
					pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
						'<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
					footerFormat: '</table>',
					shared: true,
					useHTML: true
				},
				plotOptions: {
					column: {
						pointPadding: 0.2,
						borderWidth: 0
					}
				},
				series: [{
					name: 'Tokyo',
					data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4,
						194.1, 95.6, 54.4]

				}, {
					name: 'New York',
					data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5,
						106.6, 92.3]

				}, {
					name: 'London',
					data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3,
						51.2]

				}, {
					name: 'Berlin',
					data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8,
						51.1]

				}]
			});
		}

		// On document ready
		KTUtil.onDOMContentLoaded(function () {
			KTDatatablesServerSide.init();
			barChartReport();
		});
	</script>
@endpush

