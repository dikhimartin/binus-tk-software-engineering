<?php
use Illuminate\Support\Facades\Input;

Route::group(array('prefix' => LaravelLocalization::setLocale() . '/'), function () {
	Route::get('/', 'Auth\LoginController@showLoginForm');
	Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm'); 
});

Auth::routes();

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Route::prefix('location')->group(function () {
//     Route::get('province', 'LocationController@province');
//     Route::get('city/{province_id}', 'LocationController@city');
//     Route::get('subdistrict/{city_id}', 'LocationController@subdistrict');
//     Route::get('village/{subdistrict_id}', 'LocationController@village');
// });

Route::group(array('prefix' => LaravelLocalization::setLocale() . '/api', 'namespace' => 'Api'), function () {
	Route::get('/rooms','RoomController@get_data');
	Route::get('/extra-charges','ExtraChargeController@get_data');
	Route::get('/rooms/{id}','RoomController@detail');
	Route::post('/transaction/reservation','TransactionController@reservation');
});

Route::group(array('prefix' => LaravelLocalization::setLocale() . '/admin', 'namespace' => 'Admin'), function () {
	Route::get('/dashboard', 'HomeController@index')->name('home');

	/*
	 |--------------------------------------------------------------------------
	 | MODUL Profile
	 |--------------------------------------------------------------------------
	*/
	Route::get('/profile','ProfileController@index');
	Route::put('/profile','ProfileController@update');

	/*
	|--------------------------------------------------------------------------
	| Module User Access
	|--------------------------------------------------------------------------
	*/
	Route::get('/user_access/user','UsersController@index');
	Route::get('/user_access/users','UsersController@get_data');
	Route::get('/user_access/users/{id}','UsersController@detail');
	Route::post('/user_access/users','UsersController@create');
	Route::put('/user_access/users/{id}','UsersController@update');
	Route::delete('/user_access/users/{id}','UsersController@delete');
	Route::post('/user_access/users/delete/batch','UsersController@delete_batch');
	
	/*
	|--------------------------------------------------------------------------
	| Module User Roles
	|--------------------------------------------------------------------------
	*/
	Route::get('/user_access/role',[
		'as'=>'user_access.role.index',
		'uses'=>'RoleController@index'
	]);
	Route::get('/user_access/role/create',[
		'as'=>'user_access.role.create',
		'uses'=>'RoleController@create'
	]);
	Route::get('/user_access/role/{id}',[
		'as'=>'user_access.role.edit',
		'uses'=>'RoleController@edit'
	]);
	Route::post('/user_access/role/store',[
		'as'=>'user_access.role.store',
		'uses'=>'RoleController@store'
	]);
	Route::put('/user_access/role/{id}',[
		'as'=>'user_access.role.update',
		'uses'=>'RoleController@update'
	]);
	Route::get('/user_access/roles','RoleController@get_data');
	Route::delete('/user_access/roles/{id}','RoleController@delete');
	Route::post('/user_access/roles/delete/batch','RoleController@delete_batch');
	Route::get('/user_access/roles/{id}','RoleController@detail');

	/*
	 |--------------------------------------------------------------------------
	 | Module transaction
	 |--------------------------------------------------------------------------
	*/	
	// Route::get('/transaction','TransactionController@index');
	// Route::get('/transactions','TransactionController@get_data');
	// Route::get('/transactions/{id}/detail','TransactionController@detail');
	// Route::put('/transactions/{id}/update_status','TransactionController@update_status');
	// Route::delete('/transactions/{id}','TransactionController@delete');
	// Route::post('/transactions/delete/batch','TransactionController@delete_batch');

	/*
	 |--------------------------------------------------------------------------
	 | Module Report
	 |--------------------------------------------------------------------------
	*/	
	// Route::get('/report/transaction','ReportTransactionController@index');
	// Route::get('/report/transactions','ReportTransactionController@get_data');
	// Route::get('/report/product','ReportProductController@index');
	// Route::get('/report/product/best_selling','ReportProductController@get_best_selling_product');

	/*
	 |--------------------------------------------------------------------------
	 | Module Room Type
	 |--------------------------------------------------------------------------
	*/	
	Route::get('/room-type','RoomTypeController@index');
	Route::get('/room-types','RoomTypeController@get_data');
	Route::get('/room-types/{id}','RoomTypeController@detail');
	Route::post('/room-types','RoomTypeController@create');
	Route::put('/room-types/{id}','RoomTypeController@update');
	Route::delete('/room-types/{id}','RoomTypeController@delete');
	Route::post('/room-types/delete/batch','RoomTypeController@delete_batch');

	/*
	 |--------------------------------------------------------------------------
	 | Module Facility
	 |--------------------------------------------------------------------------
	*/	
	Route::get('/facility','FacilityController@index');
	Route::get('/facilities','FacilityController@get_data');
	Route::get('/facilities/{id}','FacilityController@detail');
	Route::post('/facilities','FacilityController@create');
	Route::put('/facilities/{id}','FacilityController@update');
	Route::delete('/facilities/{id}','FacilityController@delete');
	Route::post('/facilities/delete/batch','FacilityController@delete_batch');
	
	/*
	 |--------------------------------------------------------------------------
	 | Module Extra Charge
	 |--------------------------------------------------------------------------
	*/	
	Route::get('/extra-charge','ExtraChargeController@index');
	Route::get('/extra-charges','ExtraChargeController@get_data');
	Route::get('/extra-charges/{id}','ExtraChargeController@detail');
	Route::post('/extra-charges','ExtraChargeController@create');
	Route::put('/extra-charges/{id}','ExtraChargeController@update');
	Route::delete('/extra-charges/{id}','ExtraChargeController@delete');
	Route::post('/extra-charges/delete/batch','ExtraChargeController@delete_batch');
	
	/*
	 |--------------------------------------------------------------------------
	 | Module Room
	 |--------------------------------------------------------------------------
	*/	
	Route::get('/room','RoomController@index');
	Route::get('/rooms','RoomController@get_data');
	Route::get('/rooms/{id}','RoomController@detail');
	Route::post('/rooms','RoomController@create');
	Route::put('/rooms/{id}','RoomController@update');
	Route::delete('/rooms/{id}','RoomController@delete');
	Route::post('/rooms/delete/batch','RoomController@delete_batch');
});


