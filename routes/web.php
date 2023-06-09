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

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

Route::group(['middleware' => 'auth'], function() {

    Route::group(['prefix' => 'batman'], function () {
        Route::get('/', 'SettingsController@admin_menu')->name('admin-settings');
        Route::get('/records', 'SettingsController@admin_records_mgnt')->name('admin-records-mgnt');
        Route::get('/records/{uuid}', 'SettingsController@admin_get_records_repo_links')->name('admin-records-get-repo-links');
        Route::get('/records/{uuid}/{repo}', 'SettingsController@admin_show_records_repo')->name('admin-records-get-repo-links');
    });
});

// Muthafuckin Tracking Pixels You fucking Son of bitch!
Route::group(['prefix' => 'pizza'], function () {
    Route::get('{client_uuid}', 'PixelErmPizzaController@get_pixel')->name('admin-settings');
    Route::get('{client_uuid}/pizza-lib', 'PixelErmPizzaController@get_pixel_js')->name('admin-settings');
});

Route::get('/testpush', 'MessagingController@test_push_expo_notification');

