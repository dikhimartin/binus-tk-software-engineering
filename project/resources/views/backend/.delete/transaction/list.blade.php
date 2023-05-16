@extends('layouts.backend.app')
@section('sidebarActive', $controller)

<!--begin::Vendor Stylesheets(used for this page only)-->
@push('private_css')
	<link href="{{ URL::asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
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
										<!--begin::Option-->
										<label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
											<input class="form-check-input" type="radio" name="status_transaction" value="all" checked="checked" />
											<span class="form-check-label text-gray-600">{{ __('main.all') }}</span>
										</label>
										<label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
											<input class="form-check-input" type="radio" name="status_transaction" value="pending" />
											<span class="form-check-label text-gray-600">{{ __('main.pending') }}</span>
										</label>
										<label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
											<input class="form-check-input" type="radio" name="status_transaction" value="reject" />
											<span class="form-check-label text-gray-600">{{ __('main.reject') }}</span>
										</label>
										<label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
											<input class="form-check-input" type="radio" name="status_transaction" value="finish" />
											<span class="form-check-label text-gray-600">{{ __('main.finish') }}</span>
										</label>
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
							<th>Nama</th>
							<th>Divalidasi oleh</th>
							<th>Status</th>
							<th>{{ __('main.transaction_date') }}</th>
							<th class="text-end min-w-100px">{{__('main.action')}}</th>
						</tr>
						</thead>
						<tbody class="text-gray-600 fw-semibold">
						</tbody>
					</table>
				</div>
			</div>

			<!--begin::Modal Component-->
			@component('backend.components.modal', ['modal_size' => 'modal-lg', 'modal_id' => $controller])
				@section('modal_content')
					<div class="d-flex flex-column gap-5">
						<div class="table-responsive" id="transaction-detail"></div>
					</div>
				@endsection
			@endcomponent
		</div>
	</div>
@endsection

<!--begin::Vendors Javascript(used for this page only)-->
@push('private_js')
	<script src="{{ URL::asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
	<script>
		"use strict";

		const URL_API = `{{ url('admin/transactions') }}`

		function show_transaction_detail(id) {
			// Fetching data
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
						$('<th>').text('Nama Produk').appendTo(tr);
						$('<th>').text('Harga').appendTo(tr);
						$('<th>').text('Kuantitas').appendTo(tr);
						$('<th>').text('Sub Harga').appendTo(tr);
						const tbody = $('<tbody>').appendTo(table);

						// Looping data transaction detail
						data.transaction_details.forEach(function(detail) {
							const tr = $('<tr>').appendTo(tbody);
							$('<td>').text(detail.product.name).appendTo(tr);
							$('<td>').text(IDRCurrency(detail.price)).appendTo(tr);
							$('<td>').text(detail.quantity).appendTo(tr);
							$('<td>').text(IDRCurrency(detail.sub_price)).appendTo(tr);
						});

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

		function update_status(id, status) {
			$.ajax({
				url: `transactions/${id}/update_status`,
				type: "PUT",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {
					status: status,
					_method: 'PUT' // tambahkan _method dengan nilai PUT
				},
				dataType: "JSON",
				success: function(response) {
					KTDatatablesServerSide.refresh();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					if (jqXHR.responseJSON.status.message != undefined) {
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
					order: [[4, 'desc']],
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
						{ data: 'id' },
						{ data: 'creator_name' },
						{ data: 'modifier_name' },
						{ data: 'status_transaction' },
						{ data: 'transaction_date' },
						{ data: null },
					],
					columnDefs: [
						{
							targets: 0,
							orderable: false,
							render: function (data) {
								return `<div class="form-check form-check-sm form-check-custom form-check-solid">
											<input class="form-check-input" type="checkbox" value="${data}" />
										</div>`;
							}
						},
						{
							targets: 1,
							render: function (data, type, row) {
								return `<a href="javascript:void(0)" onclick="show_transaction_detail('${row.id}')">
											<span class='text-black'>${row.creator_name}</span></br>
											<span class='text-primary'>Role : ${row.role_creator}</span>
										</a>`;
							}
						},
						{
							targets: 2,
							render: function (data, type, row) {
								var	html = `<span class='text-black'>${row.modifier_name}</span></br>
											<span class='text-primary'>Role : ${row.role_modifier}</span>`;								
								if(row.staff_name == null && row.status_transaction == "pending"){
									html = `<span class='text-info'>Belum di validasi</span>`;
								}
								return html;
							}
						},
						{
							targets: 3,
							render: function (data) {
								var arrStatus = {!! json_encode(arrStatusTransaction()) !!};
								var arrLabel = {!! json_encode(arrStatusTransactionlabel()) !!};
								var labelText = arrStatus[data] || '';
								var labelClass = arrLabel[data] || '';
								return '<span class="badge badge-' + labelClass + '">' + labelText + '</span>';
							}
						},
						{
							targets: -1,
							data: null,
							orderable: false,
							className: 'text-end',
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

										@permission('transaction-edit')
											<div class="menu-item px-3">
												<a href="javascript:void(0)" onclick="update_status('${data["id"]}', 'finish')" class="menu-link px-3" data-kt-docs-table-filter="edit_row">
													{{ __('main.finish') }}
												</a>
											</div>
										@endpermission
										
										@permission('transaction-edit')
											<div class="menu-item px-3">
												<a href="javascript:void(0)" onclick="update_status('${data["id"]}', 'reject')" class="menu-link px-3" data-kt-docs-table-filter="edit_row">
													{{ __('main.reject') }}
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
					// Add data-filter attribute
					createdRow: function (row, data, dataIndex) {
						$(row).find('td:eq(4)').attr('data-filter', data.CreditCardType);
					}
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

			// Delete customer
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
													// delete row data from server and re-draw datatable
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

