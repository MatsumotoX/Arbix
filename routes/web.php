<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'HomeController@landingPage')->name('landingPage');

Route::name('landingpage.')->prefix('/landingpage')->group(function () {
	$controller = 'LandingPageController';
	Route::get('home', $controller . '@home')->name('home');
	Route::get('setting', $controller . '@setting')->name('setting');
});

Route::get('viewfile/{mainDirectory}/{subDirectory}/{property}/{file}', 'Controller@viewFile');
Route::get('downloadfile/{mainDirectory}/{subDirectory}/{property}/{file}', 'Controller@downloadFile');

Route::name('preferences.')->prefix('/preferences/{mainDirectory}/{subDirectory}')->group(function () {
	$controller = 'Preferences\PreferenceController';
	Route::get('index', $controller . '@index')->name('index');
	Route::get('getdata', $controller . '@getData')->name('getdata');
	Route::get('getReorder', $controller . '@getReorder')->name('getReorder');
	Route::post('saveReorder', $controller . '@saveReorder')->name('saveReorder');
	Route::get('redisset', $controller . '@redisSet')->name('redisset');
	Route::get('migrate', $controller . '@migrate')->name('migrate');
	Route::post('addGroup', $controller . '@addGroup')->name('addGroup');
	Route::post('addProperty', $controller . '@addProperty')->name('addProperty');
	Route::get('deleteProperty', $controller . '@deleteProperty')->name('deleteProperty');
	Route::post('updateProperty', $controller . '@updateProperty')->name('updateProperty');
	Route::post('updateGroup', $controller . '@updateGroup')->name('updateGroup');
	Route::post('addOption', $controller . '@addOption')->name('addOption');
	Route::post('deleteOption', $controller . '@deleteOption')->name('deleteOption');
	Route::post('updateOption', $controller . '@updateOption')->name('updateOption');
});

Route::name('properties.')->prefix('/properties/{mainDirectory}/{subDirectory}')->group(function () {
	$controller = 'Properties\PropertyController';
	Route::get('test', $controller . '@test')->name('test');
	Route::get('index', $controller . '@index')->name('index');
	Route::get('create', $controller . '@create')->name('create');
	Route::get('show', $controller . '@show')->name('show');
	Route::post('show', $controller . '@getShow')->name('show');
	Route::get('redisReset', $controller . '@redisReset')->name('redisReset');
	Route::post('getProperty', $controller . '@getProperty')->name('getProperty');
	Route::post('store', $controller . '@store')->name('store');
	Route::post('update', $controller . '@update')->name('update');
	Route::post('addOrEdit', $controller . '@addOrEdit')->name('addOrEdit');
	Route::post('edit', $controller . '@edit')->name('edit');
	Route::get('import', $controller . '@import')->name('import');
	Route::post('import', $controller . '@confirmImport')->name('import');
	Route::post('checkimport', $controller . '@checkImport')->name('checkimport');
	Route::post('checkdate', $controller . '@checkDate')->name('checkdate');
	Route::get('getIdentity', $controller . '@getIdentity')->name('getIdentity');
});

Route::name('properties.')->prefix('/properties/{mainDirectory}/{subDirectory}')->group(function () {
	$controller = 'HRs\CustomerController';
	Route::get('viewCustomer', $controller . '@viewCustomer')->name('viewCustomer');
	Route::get('getContact', $controller . '@getContact')->name('getContact');
	Route::post('storeContact', $controller . '@storeContact')->name('storeContact');
	Route::post('storeNameCard', $controller . '@storeNameCard')->name('storeNameCard');
	Route::get('getContract', $controller . '@getContract')->name('getContract');
	Route::post('storeContract', $controller . '@storeContract')->name('storeContract');
	Route::get('getLocation', $controller . '@getLocation')->name('getLocation');
	Route::post('storeLocation', $controller . '@storeLocation')->name('storeLocation');
});

Route::name('properties.')->prefix('/properties/{mainDirectory}/{subDirectory}')->group(function () {
	$controller = 'Logistics\RouteController';
	Route::get('viewRoute', $controller . '@viewRoute')->name('viewRoute');
});

Route::name('line.')->prefix('/lines')->group(function () {
	$controller = 'Line\LINEController';
	Route::get('test', $controller . '@test')->name('test');
	Route::post('webhooks', $controller . '@receive')->name('receive');
	Route::get('linkAccount', $controller . '@linkAccount')->name('linkAccount');
	Route::post('linkAccount', $controller . '@storeLinkAccount')->name('linkAccount');
	Route::get('richMenu', $controller . '@richMenu')->name('richMenu');

	Route::name('settings.')->prefix('/settings')->group(function () {
		Route::name('richMenus.')->prefix('/richMenus')->group(function () {
			$controller = 'Line\RichMenuController';
			Route::get('test', $controller . '@test')->name('test');
			Route::get('index', $controller . '@index')->name('index');
			Route::get('create', $controller . '@create')->name('create');
			Route::post('store', $controller . '@store')->name('store');
			Route::get('getData', $controller . '@getData')->name('getData');
			Route::post('setRichMenu', $controller . '@setRichMenu')->name('setRichMenu');
		});
		Route::name('flexMessages.')->prefix('/flexMessages')->group(function () {
			$controller = 'Line\FlexMessageController';
			Route::get('test', $controller . '@test')->name('test');
			Route::post('testFlex', $controller . '@testFlex')->name('testFlex');
			Route::get('index', $controller . '@index')->name('index');
			Route::get('create', $controller . '@create')->name('create');
			Route::post('store', $controller . '@store')->name('store');
			Route::get('getData', $controller . '@getData')->name('getData');
		});
	});
});

Route::name('apps.')->prefix('/apps/{mainDirectory}/{subDirectory}')->group(function () {
	Route::name('leaves.')->prefix('/leaves')->group(function () {
		$controller = 'HRs\Users\LeaveController';
		$sub        = 'Leave';
		Route::get('test' . $sub, $controller . '@test' . $sub)->name('test' . $sub);
		Route::get('create' . $sub, $controller . '@create' . $sub)->name('create' . $sub);
		Route::post('store' . $sub, $controller . '@store' . $sub)->name('store' . $sub);
	});
});

Route::name('apps.')->prefix('/apps/{mainDirectory}/{subDirectory}')->group(function () {
	Route::name('users.')->prefix('/users')->group(function () {
		Route::name('settings.')->prefix('/settings')->group(function () {
			$controller = 'HRs\Users\UserController';
			Route::get('index', $controller . '@index')->name('index');
		});
	});
});

Route::name('grids.')->prefix('/grids')->group(function () {
	$controller = 'Grid\GridController';
	Route::post('dispatcher', $controller . '@dispatcher')->name('dispatcher');
	Route::get('getData', $controller . '@getData')->name('getData');
});

Route::name('googles.')->prefix('/googles')->group(function () {
	Route::name('maps.')->prefix('/maps')->group(function () {
		$controller = 'Google\MapController';
		Route::get('test', $controller . '@test')->name('test');
		Route::post('dispatcher', $controller . '@dispatcher')->name('dispatcher');
	});
});

Auth::routes(['verify' => true]);

Route::name('zoho.')->prefix('/zohos')->group(function () {
	Route::name('books.')->prefix('/books')->group(function () {
		$controller = 'Zoho\ZohoBooksController';
		Route::get('oauth2callback', $controller . '@oauth')->name('oauth');
		Route::get('getcode', $controller . '@getcode')->name('getcode');
	});
});

Route::get('/home', 'HomeController@index')->name('home');
