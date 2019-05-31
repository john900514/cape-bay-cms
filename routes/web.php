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

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/settings', 'SettingsController@index')->name('settings');

    Route::group(['prefix' => 'batman'], function () {
        Route::get('/', 'SettingsController@admin_menu')->name('admin-settings');
        Route::get('/records', 'SettingsController@admin_records_mgnt')->name('admin-records-mgnt');
        Route::get('/records/{uuid}', 'SettingsController@admin_get_records_repo_links')->name('admin-records-get-repo-links');
        Route::get('/records/{uuid}/{repo}', 'SettingsController@admin_show_records_repo')->name('admin-records-get-repo-links');
    });

    Route::group(['prefix' => 'reports'], function () {
        Route::get('/', 'ReportingController@index')->name('reporting');
        Route::get('/{uuid}', 'ReportingController@get_client_reports')->name('get-client-reports');
        Route::get('/{uuid}/{report}', 'ReportingController@get_client_report')->name('get-client-report');
    });

    Route::group(['prefix' => 'live-tracking'], function () {
        Route::get('/', 'ReportingController@tracking_menu')->name('live-tracking');
        Route::get('/{uuid}', 'ReportingController@get_client_trackers')->name('get-client-trackers');
        Route::get('/{uuid}/{tracker}', 'ReportingController@get_client_tracker')->name('get-client-tracker');
    });

});


