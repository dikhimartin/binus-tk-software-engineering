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
	@endcomponent	

	<!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
		<!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
			<!--begin::Card-->
			<div class="card">

				<!--begin::Header Datatable Component -->
				@component('backend.components.datatable-header')
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
							<div class="px-7 py-5">
								<!--begin::Input group-->
								<div class="mb-10">
									<!--begin::Label-->
									<label class="form-label fs-5 fw-semibold mb-3">Status:</label>
									<!--begin::Options-->
									<div class="d-flex flex-column flex-wrap fw-semibold" data-kt-docs-table-filter="status_transaction">
									</div>
								</div>
								<!--begin::Actions-->
								<div class="d-flex justify-content-end">
									<button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true" data-kt-docs-table-filter="reset">{{ __('main.reset') }}</button>
									<button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true" data-kt-docs-table-filter="filter">{{ __('main.apply') }}</button>
								</div>
							</div>
						</div>
					@endslot
				@endcomponent
					
				<div class="card-body pt-0">
					<!--begin::Datatable-->
					<table id="kt_datatable_server_side" class="table align-middle table-row-dashed fs-6 gy-5">
						<thead>
						<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
							<th class="w-10px pe-2">
								<div class="form-check form-check-sm form-check-custom form-check-solid me-3">
									<input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_datatable_server_side .form-check-input" value="1"/>
								</div>
							</th>
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
	<script>
		"use strict";

		const URL_API = `{{ url('admin/transactions') }}`

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
			var filterStatus;

			// Private functions
			var initDatatable = function () {
				dt = $("#kt_datatable_server_side").DataTable({
					searchDelay: 500,
					processing: true,
					serverSide: true,
					order: [[1, 'desc']],
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
						{data: 'id' },
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
							orderable: false,
							render: function (data, type, row) {
								return `<div class="form-check form-check-sm form-check-custom form-check-solid">
											<input class="form-check-input" type="checkbox" value="${row.id}" />
										</div>`;
							}
						},
						{	
							targets: 1,
							createdCell: function (td, cellData, rowData, row, col) {
								$(td).addClass('td-class');
							},
							render: function (data, type, row) {
								return `<a href="javascript:void(0)" onclick="show_transaction_detail('${row.id}')">${row.transaction_code}</a>`;
							}
						},
						{	
							targets: 2,
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
							targets: 3,
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
							targets: 4,
							createdCell: function (td, cellData, rowData, row, col) {
								$(td).addClass('td-class');
							},
							render: function (data, type, row) {
								return convertDateTime(data);
							}
						},
						{	
							targets: 5,
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

										<!--begin::Menu item-->
										@permission('transaction-delete')
											<div class="menu-item px-3">
												<a href="javascript:void(0)" class="menu-link px-3" data-kt-docs-table-filter="delete_row">
													{{ __('main.delete') }}
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
					initToggleToolbar();
					toggleToolbars();
					handleDeleteRows();
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

			// Filter Datatable
			var handleFilterDatatable = () => {
				// Select filter options
				filterStatus = document.querySelectorAll('[data-kt-docs-table-filter="status_transaction"] [name="status_transaction"]');
				const filterButton = document.querySelector('[data-kt-docs-table-filter="filter"]');

				// Filter datatable on submit
				filterButton.addEventListener('click', function () {
					// Get filter values
					let statusValue = '';

					// Get status value
					filterStatus.forEach(r => {
						if (r.checked) {
							statusValue = r.value;
						}

						// Reset status value if "All" is selected
						if (statusValue === 'all') {
							statusValue = '';
						}
					});

					// Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
					dt.search(statusValue).draw();
				});
			}

			// Delete 
			var handleDeleteRows = () => {
				// Select all delete buttons
				const deleteButtons = document.querySelectorAll('[data-kt-docs-table-filter="delete_row"]');

				deleteButtons.forEach(d => {
					// Delete button on click
					d.addEventListener('click', function (e) {
						e.preventDefault();

						// Select parent row
						const parent = e.target.closest('tr');

						// Get data
						const id = parent.querySelector('input[type="checkbox"]').value;
						const infoField = parent.querySelectorAll('td')[1].innerText;

						if (id != undefined){
							// SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
							Swal.fire({
								text: "{{ __('main.are_you_sure_want_to_delete' )}} " + infoField + "?",
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
									$.ajax({
										url : URL_API + '/' + id,
										type: "DELETE",
										dataType: "JSON",
										headers: {
											'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
										},												
										success: function(response){
											if (response.status.code === 200) {
												Swal.fire({
													text: "{{ __('main.you_have_deleted') }} " + infoField + "!.",
													icon: "success",
													buttonsStyling: false,
													confirmButtonText: "Ok, got it!",
													customClass: {
														confirmButton: "btn fw-bold btn-primary",
													}
												}).then(function () {
													dt.draw();
												});
											}
										},
										error: function (jqXHR, textStatus, errorThrown) {
											if (jqXHR.responseJSON.status.message != undefined){
												errorThrown = jqXHR.responseJSON.status.message;
											}
											SwalError(errorThrown);
										}
									});								
								} else if (result.dismiss === 'cancel') {
									SwalError(infoField + " {{ __('main.was_not_deleted') }}");
								}
							});
						}
					})
				});
			}

			// Reset Filter
			var handleResetForm = () => {
				// Select reset button
				const resetButton = document.querySelector('[data-kt-docs-table-filter="reset"]');

				// Reset datatable
				resetButton.addEventListener('click', function () {
					// Reset status type
					filterStatus[0].checked = true;

					// Reset datatable --- official docs reference: https://datatables.net/reference/api/search()
					dt.search('').draw();
				});
			}

			// Init toggle toolbar
			var initToggleToolbar = function () {
				// Toggle selected action toolbar
				// Select all checkboxes
				const container = document.querySelector('#kt_datatable_server_side');
				const checkboxes = container.querySelectorAll('[type="checkbox"]');

				// Select elements
				const deleteSelected = document.querySelector('[data-kt-docs-table-select="delete_selected"]');

				// Toggle delete selected toolbar
				checkboxes.forEach(c => {
					// Checkbox on click event
					c.addEventListener('click', function () {
						setTimeout(function () {
							toggleToolbars();
						}, 50);
					});
				});

				// Deleted selected rows
				deleteSelected.addEventListener('click', function () {
					// SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
					var post_arr = [];
					const allCheckboxes = container.querySelectorAll('tbody [type="checkbox"]');
					allCheckboxes.forEach(c => {
						if (c.checked) {
							const value = c.value;
							if (value !== undefined && value !== "" && !post_arr.includes(value)) {
								post_arr.push(value);
							}
						}
					});
					if(post_arr.length > 0){
						Swal.fire({
							text: "{{ __('main.are_you_sure_you_want_to_delete_selected_data') }}",
							icon: "warning",
							showCancelButton: true,
							buttonsStyling: false,
							showLoaderOnConfirm: true,
							confirmButtonText: "{{ __('main.yes_deleted') }}",
							cancelButtonText: "{{ __('main.no_cancel') }}",
							customClass: {
								confirmButton: "btn fw-bold btn-danger",
								cancelButton: "btn fw-bold btn-active-light-primary"
							},
						}).then(function (result) {
							if (result.value) {
								$.ajax({
									url: URL_API + '/delete/batch',
									type: 'POST',
									data: { id: post_arr},
									headers: {
										'X-HTTP-Method-Override': "POST",
										'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
									},								
									success: function(response){
										Swal.fire({
											text: "{{ __('main.you_have_deleted_all_selected_data') }}",
											icon: "success",
											buttonsStyling: false,
											confirmButtonText: "Ok, got it!",
											customClass: {
												confirmButton: "btn fw-bold btn-primary",
											}
										}).then(function () {
											// delete row data from server and re-draw datatable
											dt.draw();
										});
									},
									error: function (jqXHR, textStatus, errorThrown) {
										if (jqXHR.responseJSON.status.message != undefined){
											errorThrown = jqXHR.responseJSON.status.message;
										}
										SwalError(errorThrown);
									}
								});
	
								// Remove header checked box
								const headerCheckbox = container.querySelectorAll('[type="checkbox"]')[0];
								headerCheckbox.checked = false;
	
							} else if (result.dismiss === 'cancel') {
								SwalError(`{{ __('main.selected_data_was_not_deleted') }}`);
							}
						});
					}
				});


			}

			// Toggle toolbars
			var toggleToolbars = function () {
				// Define variables
				const container = document.querySelector('#kt_datatable_server_side');
				const toolbarBase = document.querySelector('[data-kt-docs-table-toolbar="base"]');
				const toolbarSelected = document.querySelector('[data-kt-docs-table-toolbar="selected"]');
				const selectedCount = document.querySelector('[data-kt-docs-table-select="selected_count"]');

				// Select refreshed checkbox DOM elements
				const allCheckboxes = container.querySelectorAll('tbody [type="checkbox"]');

				// Detect checkboxes state & count
				let checkedState = false;
				let count = 0;

				// Count checked boxes
				allCheckboxes.forEach(c => {
					if (c.checked) {
						checkedState = true;
						count++;
					}
				});

				// Toggle toolbars
				if (checkedState) {
					selectedCount.innerHTML = count;
					toolbarBase.classList.add('d-none');
					toolbarSelected.classList.remove('d-none');
				} else {
					toolbarBase.classList.remove('d-none');
					toolbarSelected.classList.add('d-none');
				}
			}

			// Public methods
			return {
				init: function () {
					initDatatable();
					handleSearchDatatable();
					initToggleToolbar();
					handleFilterDatatable();
					handleDeleteRows();
					handleResetForm();
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

