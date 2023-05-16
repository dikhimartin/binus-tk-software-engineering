@extends('layouts.backend.app')
@section('sidebarActive', $controller)

<!--begin::Vendor Stylesheets(used for this page only)-->
@push('private_css')
	<link href="{{ URL::asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
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
									<div class="d-flex flex-column flex-wrap fw-semibold" data-kt-docs-table-filter="status">
										<!--begin::Option-->
										<label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
											<input class="form-check-input" type="radio" name="status" value="all" checked="checked" />
											<span class="form-check-label text-gray-600">{{ __('main.all') }}</span>
										</label>
										<!--begin::Option-->
										<label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
											<input class="form-check-input" type="radio" name="status" value="0" />
											<span class="form-check-label text-gray-600">{{ __('main.active') }}</span>
										</label>
										<!--begin::Option-->
										<label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
											<input class="form-check-input" type="radio" name="status" value="1" />
											<span class="form-check-label text-gray-600">{{ __('main.non-active') }}</span>
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
					
					@slot('button_slot')
						<!--begin::Add data-->
						@permission('roles-create')
							<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(),URL::to( 'admin/user_access/role/create' ))}}" type="button" class="btn btn-primary" >{{__('main.add_new')}}</a>
						@endpermission
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
							<th>{{ __('main.name') }}</th>
							<th>{{ __('main.description') }}</th>
							<th>Status</th>
							<th>{{ __('main.created_date') }}</th>
							<th>{{ __('main.updated_date') }}</th>
							<th class="text-end min-w-100px">{{__('main.action')}}</th>
						</tr>
						</thead>
						<tbody class="text-gray-600 fw-semibold">
						</tbody>
					</table>
				</div>

				<!--begin::Modal Component-->
				@component('backend.components.modal', ['modal_size' => 'modal-lg', 'modal_id' => 'modal_permission'])
					@section('modal_content')
						<div class="d-flex flex-column gap-5">
							<div class="d-flex flex-column gap-1">
								<p class="fw-bold text-primary fs-5">{{__('main.name_group')}}</p>
								<p class="fs-6 text-muted" id="name_group"></p>
							</div>
							<div class="d-flex flex-column gap-1">
								<p class="fw-bold text-primary fs-5">{{__('main.list_permissions')}}</p>
								<div class="d-flex flex-row">
									<div class="d-flex flex-column flex-row-fluid">
										<div class="d-flex flex-row flex-column-fluid">
											<div id="permission_list"></div>
										</div>
									</div>
								</div>
							</div>

						</div>
					@endsection
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

		const URL_API = `{{ url('admin/user_access/roles') }}`

		function detail(id){
			$('#modal_permission_header_title').text(`{{ __('main.role') }}`);
			// Fetching data
			$.ajax({
				url : URL_API + '/' + id,
				type: "GET",
				dataType: "JSON",             
				success: function(response){
					if (response.status.code === 200) {
						const data = response.data;
						$('#name_group').text(data.role.name);

						var permissions = "";
						data.permissions.forEach(function(permission, index) {
							permissions += '<div class="d-flex flex-row-fluid ' + (index == 0 ? '' : 'mt-6') + '">';
							permissions += '<div class="d-flex flex-column text-gray-600">';
							permissions += '<div class="fw-bold">' + permission.name + '</div>';
							permission.list.forEach(function(item) {
								permissions += '<div class="d-flex align-items-center py-2"><span class="bullet bg-primary me-3"></span>' + item.display_name + '</div>';
							});
							permissions += '</div></div>';
						});
						$("#permission_list").html(permissions);

						// Show the modal
						$('#modal_permission').modal('show');				
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					if (jqXHR.responseJSON.status.message != undefined){
						errorThrown = jqXHR.responseJSON.status.message;
					}
					SwalError(errorThrown);
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
						{ data: 'name' },
						{ data: 'description' },
						{ data: 'status' },
						{ data: 'created_at' },
						{ data: 'updated_at' },
						{ data: null },
					],
					columnDefs: [
						{
							targets: 0,
							orderable: false,
							render: function (data, type, row) {
								var checked = '';
								if (row["id"] != 1){
									checked = `
										<div class="form-check form-check-sm form-check-custom form-check-solid">
											<input class="form-check-input" type="checkbox" value="${data}" />
										</div>`;
								}
								return checked;
							}
						},
						{
							targets: 1,
							render: function (data, type, row) {
								return `<a href="javascript:void(0)" onclick="detail(`+ row.id +`)" class="text-gray-800 text-hover-primary mb-1">`+ row.name +`</a>`;
							}
						},
						{
							targets: 3,
							render: function (data) {
								var labelClass = data == 0 ? 'primary' : 'danger';
								var labelText = data == 0 ? '{{ __('main.active') }}' : '{{ __('main.non-active') }}';
								return '<span class="badge badge-' + labelClass + '">' + labelText + '</span>';
							}
						},
						{
							targets: -1,
							data: null,
							orderable: false,
							className: 'text-end',
							render: function (data, type, row) {
								var action = '';
								var id = row["id"]; 
								if (id != 1){
									action = 	`
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
												@permission('users-edit')
													<div class="menu-item px-3">
														<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(),URL::to( 'admin/user_access/role/`+ id +`' ))}}" class="menu-link px-3" data-kt-docs-table-filter="edit_row">
															{{ __('main.edit') }}
														</a>
													</div>
												@endpermission

												<!--begin::Menu item-->
												@permission('users-delete')
													<div class="menu-item px-3">
														<a href="javascript:void(0)" class="menu-link px-3" data-kt-docs-table-filter="delete_row">
															{{ __('main.delete') }}
														</a>
													</div>
												@endpermission
											</div>`;
								}
								return action;								
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
				filterStatus = document.querySelectorAll('[data-kt-docs-table-filter="status"] [name="status"]');
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

