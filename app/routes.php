<?php

// Фильтры параметров поискового запроса и получения данных о турах
Route::get('create-search-request', array('before' => 'tParamsFilter', 'uses' => 'VTSearchController@createSearchRequest'));
Route::get('get-tours-data', array('before' => 'tRequestIdFilter', 'uses' => 'VTSearchController@getToursData'));
Route::get('get-min-tour-price', array('before' => 'tGetMinPriceParamsFilter', 'uses' => 'VTSearchController@getMinPrice'));
Route::get('save-tour-order', array('before' => 'tSaveOrderFilter', 'uses' => 'VTSearchController@saveTourOrder'));
Route::get('get-hotel-info', array('before' => 'hInfoParamsFilter','uses' => 'VTSearchController@getHotelInfo'));

// CSRF Filter
Route::when('*', 'csrf', ['post', 'put', 'patch', 'delete']);

// Тестовый роут для вывода вьюхи в админке
Route::get('/', function() {
	return View::make('hello');
});

// Роуты для авторизации
Route::get('login', array('uses' => 'AdminController@showLogin'));
Route::post('login', array('uses' => 'AdminController@doLogin'));
Route::get('logout', array('uses' => 'AdminController@doLogout'));

// Тестим ACL Entrust - работает!
// Route::get('hello', function() {
// 	Auth::logout();
// 	Auth::loginUsingId(2);

// 	$user = Auth::user();

// 	if ($user->hasRole('Owner')) {
// 		return 'You are the greatest Lord!';
// 	} else return 'You are just manager! :(';
// });
