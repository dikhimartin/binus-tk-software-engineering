@extends('layouts.backend.app')
@section('sidebarActive', $controller)

<!--begin::Vendor Stylesheets(used for this page only)-->
@push('private_css')
	<link href="{{ URL::asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <!--begin::Toolbar Component-->
	@component('backend.components.toolbar', ['pages_title' => __('main.add_new'), 'sub_menu' => $pages_title, 'sub_menu_link' => LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(),URL::to( 'admin/user_access/role' ))])
	@endcomponent	

	<!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
		<!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
			<div class="d-flex flex-column flex-lg-row">
				<div class="flex-column flex-lg-row-auto w-100  mb-10">
					<div class="card card-flush">
						{!! Form::open(array('route' => 'user_access.role.store','method'=>'POST')) !!}
							<div class="card-header">
								<div class="card-title">
									<h4 class="card-title text-primary">{{__('main.information_group')}} :</h4>
								</div>
							</div>
							<!--begin::Card body-->
							<div class="card-body pt-0">
								@if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
								<!--begin::Permissions-->
								<div class="d-flex flex-column  me-n7 pe-7" >
										<!--begin::Input group-->
										<div class="fv-row mb-10 fv-plugins-icon-container">
											<label class="fs-5 fw-bold form-label mb-2">
												<span class="required">{{__('main.name_group')}}</span>
											</label>
											{!! Form::text('name', null, array('placeholder' => __('main.name_group'),'class' => 'form-control form-control-solid')) !!}
										</div>
										<div class="fv-row mb-10 fv-plugins-icon-container">
											<label class="fs-5 fw-bold form-label mb-2">
												<span class="required">{{__('main.display_name')}}</span>
											</label>
											{!! Form::text('display_name', null, array('placeholder' => __('main.display_name'),'class' => 'form-control form-control-solid')) !!}
										</div>

										<div class="fv-row mb-10 fv-plugins-icon-container">
											<label class="fs-5 fw-bold form-label mb-2">
												<span class="required">{{__('main.description')}}</span>
											</label>
											{!! Form::textarea('description', null, array('placeholder' => "Description",'class' => 'form-control', 'data-kt-autosize="true"')) !!}
										</div>

										<div class="fv-row mb-20 fv-plugins-icon-container">
											<label class="fs-6 fw-semibold mb-2">Status</label>
											<div class="d-flex">
												<label class="form-check form-check-sm form-check-custom form-check-solid me-5">
												<input class="form-check-input" type="radio" value="0" id="flexCheckChecked1" name="status" checked="">
													<label class="form-check-label" for="flexCheckDefault1">
														{{ __('main.active') }}
													</label>
												</label>
												<label class="form-check form-check-sm form-check-custom form-check-solid">
													<input class="form-check-input" type="radio" value="1" id="flexCheckChecked1" name="status">
													<label class="form-check-label" for="flexCheckDefault1">
														{{ __('main.non-active') }}
													</label>
												</label>
											</div>        
										</div>

										<!--begin::Permissions-->
										<div class="fv-row">
											<!--begin::Label-->
											<div class="col-xs-12 col-sm-12 col-md-12">
											<div class="form-group">
												<h4 class="card-title text-primary">{{__('main.permissions_menu')}}</h4>
												<p class="card-subtitle">{{__('main.permissions_detail')}}</p>
												<hr>                                         
											</div>
											<label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mb-5">
												<input type="checkbox" class="form-check-input" id="parent-checkbox-hapus" onClick="toggle(this)">
												<span class="form-check-label text-black">{{__('main.select_all')}}</span>
											</label>
											<p style="margin-bottom: 20px"></p>
											<div class="form-group">
												<div class="row show-grid">
													@foreach ($arrGroup as $groupName => $data)
													<div class="col-sm-3" style="min-height: 175px;">
														<fieldset>
															<legend class="mb-5">{{ strtoupper($groupName) }}</legend>
															@foreach($data as $value)
																<div>
																	<label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mb-5">
																		{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'form-check-input')) }}
																		<span class="form-check-label text-black">{{ $value->display_name }}</span>
																	</label>
																</div>
															@endforeach
														</fieldset>
													</div>
													@endforeach
												</div>
												<p style="margin-bottom: 50px"></p>
											</div>
										</div>									
									</div>
								</div>
							</div>
							<!--begin::Card footer-->
							<div class="card-footer pt-0">
								<button type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
									<span class="indicator-label">{{ __('main.save') }}</span>
									<span class="indicator-progress">Please wait...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
								</button>
							</div>
						{!! Form::close() !!}    
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
<!--begin::Vendors Javascript(used for this page only)-->
@push('private_js')
	<script>
		function toggle(source) {
			checkboxes = document.getElementsByName('permission[]');
			for(var i=0, n=checkboxes.length;i<n;i++) {
				checkboxes[i].checked = source.checked;
			}
		}   
	</script>
@endpush



