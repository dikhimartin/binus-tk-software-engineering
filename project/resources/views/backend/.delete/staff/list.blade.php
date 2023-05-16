@extends('layouts.backend.app')
@section('sidebarActive', $controller)

<!--begin::Vendor Stylesheets(used for this page only)-->
@push('private_css')
	<link href="{{ URL::asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ URL::asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css"/>
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
									<label class="form-label fs-5 fw-semibold mb-3">{{ __('main.gender') }}</label>
									<!--begin::Options-->
									<div class="d-flex flex-column flex-wrap fw-semibold" data-kt-docs-table-filter="gender">
										<!--begin::Option-->
										<label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
											<input class="form-check-input" type="radio" name="gender" value="all" checked="checked" />
											<span class="form-check-label text-gray-600">{{ __('main.all') }}</span>
										</label>
										<!--begin::Option-->
										<label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
											<input class="form-check-input" type="radio" name="gender" value="male" />
											<span class="form-check-label text-gray-600">{{ __('main.male') }}</span>
										</label>
										<!--begin::Option-->
										<label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
											<input class="form-check-input" type="radio" name="gender" value="female" />
											<span class="form-check-label text-gray-600">{{ __('main.female') }}</span>
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
						@permission('staff-create')
							<button type="button" class="btn btn-primary" onclick="add()">{{__('main.add_new')}}</button>
						@endpermission
					@endslot
				@endcomponent
				
				<!--begin::Card body-->
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
							<th>{{ __('main.users') }}</th>
							<th>{{ __('main.gender') }}</th>
							<th>{{ __('main.created_date') }}</th>
							<th>{{ __('main.updated_date') }}</th>
							<th class="text-end min-w-100px">{{__('main.action')}}</th>
						</tr>
						</thead>
						<tbody class="text-gray-600 fw-semibold">
						</tbody>
					</table>
				</div>
			</div>

			<!--begin::Modal Component-->
			@component('backend.components.form-modal', ['modal_size' => 'modal-lg', 'modal_id' => $controller])
				@section('form_modal')
					<ul class="nav nav-tabs nav-line-tabs mb-10 fs-6">
						<li class="nav-item">
							<a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Profil</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3">Akun</a>
						</li>
					</ul>

					<div class="tab-content" id="myTabContent">
						<!-- Profile -->
						<div class="tab-pane show active" id="kt_tab_pane_1" role="tabpanel">
							<div class="fv-row mb-7">
								<label class="d-block fw-semibold fs-6 mb-5">Avatar</label>
								<style>.image-input-placeholder { background-image: url('{{ URL::asset("assets/media/svg/files/blank-image.svg") }}'); } [data-bs-theme="dark"] .image-input-placeholder { background-image: url('{{ URL::asset("assets/media/svg/files/blank-image-dark.svg") }}'); }</style>
								<div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
									<div class="image-input-wrapper w-125px h-125px"></div>
									<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
										<i class="bi bi-pencil-fill fs-7"></i>
										<input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
										<input type="hidden" name="avatar_remove" />
									</label>
									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
										<i class="bi bi-x fs-2"></i>
									</span>
									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
										<i class="bi bi-x fs-2"></i>
									</span>
								</div>
								<div class="form-text">{{ __('main.allowed_file_types', ['value' => "png, jpg, jpeg"]) }}</div>
								<div class="text-primary">{{ __('main.info_image_pixels', ['value' => "150 x 150"]) }}</div>
							</div>
							
							<div class="fv-row mb-7">
								<label class="required fs-6 mb-2">{{ __('main.name') }}</label>
								<input type="text" name="name" class="form-control staff- mb-3 mb-lg-0" placeholder="{{ __('main.name') }}"  />
							</div>

							<div class="fv-row mb-7">
								<label class="required fw-semibold fs-6 mb-2">{{ __('main.email') }}</label>
								<input type="email" name="email" class="form-control staff- mb-3 mb-lg-0" placeholder="{{ __('main.email') }}" />
							</div>

							<div class="fv-row mb-7">
								<label class="required fs-6 fw-semibold mb-2">{{ __('main.gender') }}</label>
								<div class="d-flex">
									<label class="form-check form-check-sm form-check-custom form-check-solid me-5">
									<input class="form-check-input" type="radio" value="male" id="flexCheckChecked1" name="gender" checked="">
										<label class="form-check-label" for="flexCheckDefault1">
											{{ __('main.male') }}
										</label>
									</label>
									<label class="form-check form-check-sm form-check-custom form-check-solid">
										<input class="form-check-input" type="radio" value="female" id="flexCheckChecked1" name="gender">
										<label class="form-check-label" for="flexCheckDefault1">
											{{ __('main.female') }}
										</label>
									</label>
								</div>        
							</div>
											
						
						</div>

						<!-- Account -->
						<div class="tab-pane" id="kt_tab_pane_3" role="tabpanel">
							<div class="fv-row mb-7">
								<label class="required fw-semibold fs-6 mb-2">{{ __('main.username') }}</label>
								<input type="text" name="username" class="form-control staff- mb-3 mb-lg-0" placeholder="{{ __('main.username') }}" />
							</div>
							
							<!-- password-->
							<div class="mb-10 fv-row" data-kt-password-meter="true">
								<div class="mb-1">
									<label class="required form-label fw-semibold fs-6 mb-2">
									{{ __('passwords.password') }}
									</label>
									<div class="position-relative mb-3">
										<input class="form-control form-control-lg staff-" type="password" placeholder="" name="password" autocomplete="new-password"  />
										<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
											<i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
											<i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
										</span>
									</div>
									<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
									</div>
								</div>
								<div class="text-muted">
									{{ __('passwords.rules_password_description') }}
								</div>
							</div>

							<!-- confirm password-->
							<div class="fv-row mb-10">
								<label class="form-label fw-semibold fs-6 mb-2">{{__('main.confirm')}} {{ __('passwords.password') }}</label>
								<input class="form-control form-control-lg staff-" type="password" placeholder="" name="password_confirmation" autocomplete="off" />
							</div>
					</div>
				@endsection
			@endcomponent
		</div>
	</div>	
@endsection


<!--begin::Vendors Javascript(used for this page only)-->
@push('private_js')
	<script src="{{ URL::asset('assets/plugins/global/plugins.bundle.js') }}"></script>
	<script src="{{ URL::asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>

	<script>
		"use strict";

		const URL_API = `{{ url('admin/staffs') }}`

		// Function definition
		function add() {
			// Reset the form
			KTModalForm.resetForm();

			// Set text
			$('#{{ $controller  }}_submit_text').text(`{{ __('main.save') }}`);
			$('#{{ $controller  }}_header_title').text(`{{ __('main.create_new') }}`);

			// Set method and values
			$('input[name="_method"]').val('post');
			$('input[name="avatar_remove"]').val(null);

			// Set Default avatar
			var path = `{{ config('app.url') }}`;
			var avatar = path + '/images/profile/anonymous.png';
			$('.image-input-wrapper').css('background-image', 'url(' + avatar + ')');

			// Show the modal
			$('#{{ $controller  }}_trigger').modal('show');
		}	

		function edit(id) {
			// Reset the form
			KTModalForm.resetForm();

			// Set text
			$('#{{ $controller  }}_submit_text').text(`{{ __('main.update') }}`);
			$('#{{ $controller  }}_header_title').text(`{{ __('main.edit_data') }}`);

			// Set method and values
			$('input[name="_method"]').val('put');
			$('input[name="_id"]').val(id);
			$('input[name="avatar_remove"]').val(null);

			// Fetching data
			$.ajax({
				url : URL_API + '/' + id,
				type: "GET",
				dataType: "JSON",             
				success: function(response){
					if (response.status.code === 200) {
						const data = response.data;

						$('input[name="name"]').val(data.name);
						$('input[name="email"]').val(data.email);
						$('input[name="username"]').val(data.username);
						
						// Set the radio button value based on the gender value
						$('input[name="gender"][value="' + data.gender + '"]').prop('checked', true);

						// Set Image path
						var path = `{{ config('app.url') }}`;
						var avatar = path + '/images/profile/anonymous.png';
						if (data.avatar_relative_path != null){
							avatar = path + '/' + data.avatar_relative_path; 
						}						
						$('.image-input-wrapper').css('background-image', 'url(' + avatar + ')');
						
						// Show the modal
						$('#{{ $controller  }}_trigger').modal('show');				
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
			var filterGender;

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
						{ data: 'gender' },
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
								if (row["role_id"] != 1){
									checked = 	`<div class="form-check form-check-sm form-check-custom form-check-solid">
													<input class="form-check-input" type="checkbox" value="${data}" />
												</div>`;
								}
								return checked;
							}
						},
						{	
							targets: 1,
							createdCell: function (td, cellData, rowData, row, col) {
								$(td).addClass('d-flex align-items-center');
							},
							render: function (data, type, row) {
								var path = `{{ config('app.url') }}`;
								var avatar = path + '/images/profile/anonymous.png';
								if (row.avatar_relative_path != null){
									avatar = path + '/' + row.avatar_relative_path; 
								}

								var action = '';
								if (row["role_id"] != 1){
									action = `onclick="edit('`+ row.id +`')"`;
								}

								var html = `<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
									<a href="javascript:void(0)">
										<div class="symbol-label">
											<img src="`+ avatar +`" alt="`+ row.name +`" class="w-100">
										</div>
									</a>
								</div>`;
									
								html += `<div class="d-flex flex-column">
											<a href="javascript:void(0)" `+ action +` class="text-gray-800 text-hover-primary mb-1">`+ row.name +`</a>
											<span>`+ row.email +`</span>
										</div>`;

								return html;
							}
						},
						{
							targets: 2,
							render: function (data, type, row) {
								var genderArr = <?php echo json_encode(arrGender()); ?>;
        						return genderArr[row.gender];
							}
						},
						{
							targets: -1,
							data: null,
							orderable: false,
							className: 'text-end',
							render: function (data, type, row) {
								var action = '';
								if (row["role_id"] != 1){
									action = 	`<a href="javascript:void(0)" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
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
													@permission('staff-edit')
														<div class="menu-item px-3">
															<a href="javascript:void(0)" onclick="edit('`+ data["id"] +`')" class="menu-link px-3" data-kt-docs-table-filter="edit_row">
																{{ __('main.edit') }}
															</a>
														</div>
													@endpermission

													<!--begin::Menu item-->
													@permission('staff-delete')
														<div class="menu-item px-3">
															<a href="javascript:void(0)" class="menu-link px-3" data-kt-docs-table-filter="delete_row">
																{{ __('main.delete') }}
															</a>
														</div>
													@endpermission
												</div>`
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
				filterGender = document.querySelectorAll('[data-kt-docs-table-filter="gender"] [name="gender"]');
				const filterButton = document.querySelector('[data-kt-docs-table-filter="filter"]');

				// Filter datatable on submit
				filterButton.addEventListener('click', function () {
					// Get filter values
					let genderValue = '';

					// Get status value
					filterGender.forEach(r => {
						if (r.checked) {
							genderValue = r.value;
						}

						// Reset status value if "All" is selected
						if (genderValue === 'all') {
							genderValue = '';
						}
					});

					// Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
					dt.search(genderValue).draw();
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
					filterGender[0].checked = true;

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

		var KTModalForm = function () {
			var submitButton;
			var validator;
			var form;
			var modal;
		
			// Init form inputs
			var handleForm = function () {

				// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
				validator = FormValidation.formValidation(
					form,
					{
						fields: {
							// Profile
							'name': {
								validators: {
									notEmpty: {
										message: `Name {{ __('main.is_required') }}`
									}
								}
							},
							'email': {
								validators: {
									notEmpty: {
										message: `Email {{ __('main.is_required') }}`
									},
									emailAddress: {
										multiple: true,
										message: `{{ __('main.not_valid_email') }}`,
									},
								}
							},							
							'gender': {
								validators: {
									notEmpty: {
										message: `{{ __('main.gender') }} {{ __('main.is_required') }}`
									}
								}
							},
							'username': {
								validators: {
									notEmpty: {
										message: `Username {{ __('main.is_required') }}`
									},
									stringLength: {
										min: 6,
										max: 20,
										message: 'The username must be more than 6 and less than 20 characters long'
									}
								}
							},
							'password': {
								validators: {
									stringLength: {
										min: 6,
										message: `{{ __('passwords.password_characters_length', ['value' => "6"]) }}`
									},
									callback: {
										message: `{{ __('auth.please_enter_valid_password') }}`,
										callback: function (input) {
											if (input.value.length > 0) {
												// Check if password_confirmation field is empty
												const password_confirmation_input = form.querySelector('[name="password_confirmation"]');
												if (password_confirmation_input.value.length === 0) {
													password_confirmation_input.setCustomValidity('{{ __("main.confirm") }} {{ __("passwords.password") }} {{ __("main.is_required") }}');
												} else {
													password_confirmation_input.setCustomValidity('');
												}
												return validatePassword();
											}
										}
									}
								}
							},
							'password_confirmation': {
								validators: {
									identical: {
										compare: function () {
											return form.querySelector('[name="password"]').value;
										},
										message: `{{ __('passwords.the_password_and_its_confirm_are_not_the_same') }}`
									}
								}
							}
						},
						plugins: {
							trigger: new FormValidation.plugins.Trigger(),
							bootstrap: new FormValidation.plugins.Bootstrap5({
								rowSelector: '.fv-row',
								eleInvalidClass: '',
								eleValidClass: ''
							})
						}
					}
				);

				// Action buttons
				submitButton.addEventListener('click', function (e) {
					e.preventDefault();

					// Validate form before submit
					if (validator) {
						validator.validate().then(function (status) {
							console.log('validated!');

							if (status == 'Valid') {
								submitButton.setAttribute('data-kt-indicator', 'on');
								
								// Disable submit button whilst loading
								submitButton.disabled = true;

								// set data
								var url;
								var method = form._method.value;
								var formData = new FormData(document.querySelector('#{{ $controller  }}'));
								if (form._method.value == "post"){
									url = URL_API;
								}else if  (method == "put"){
									url = URL_API + "/" + form._id.value;
								}
								submitForm(url, method, formData);								

							} else {
								ToastrError(`{{ __('main.sorry_looks_like_there_are_some_errors_detected_please_try_again') }}`);
							}
						});
					}
				});

				// Send data to server | POST | PUT
				function submitForm(url, method, formData) {
					submitButton.disabled = false;
					$.ajaxSetup({
						headers: {
							'X-HTTP-Method-Override': method
						}
					});
					$.ajax({
						url : url,
						type: "POST",
						data: formData,
						contentType: false,
						processData: false,
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},						
						dataType: "JSON",
						success: function(response){
							var messages = "{{ __('main.data_added_succesfully') }}";
							if(method.toLowerCase() == "put"){
								messages = "{{ __('main.data_succesfully_changed') }}";
							}

							submitButton.removeAttribute('data-kt-indicator');

							ToastrSuccess(messages);
							// Refresh the datatable
							if (typeof KTDatatablesServerSide !== 'undefined') {
								KTDatatablesServerSide.refresh();
							}
							// Close the modal
							if (typeof modal !== 'undefined') {
								modal.hide();
							}

							// Enable submit button after loading
							submitButton.disabled = false;
						},
						error: function (jqXHR, textStatus, errorThrown) {
							ToastrErrorValidation(jqXHR);

							submitButton.removeAttribute('data-kt-indicator');
							submitButton.disabled = false;
						}
					}); 
				}	
			}

			// Reset all select and form values
			var resetForm = function () {
				form.reset();
				var selects = form.querySelectorAll('select');
				if (selects !== null && selects !== undefined) {
					selects.forEach(select => {
						$(select).val('').trigger('change');
					});
				}				
			}



			return {
				// Public functions
				init: function () {
					// Elements
					modal = new bootstrap.Modal(document.querySelector('#{{ $controller  }}_trigger'));

					form = document.querySelector('#{{ $controller  }}');
					submitButton = form.querySelector('#{{ $controller  }}_submit');
					handleForm();
				},
				resetForm: resetForm 
			};
		}();

		// On document ready
		KTUtil.onDOMContentLoaded(function () {
			KTModalForm.init();
			KTDatatablesServerSide.init();
		});
	</script>
@endpush
