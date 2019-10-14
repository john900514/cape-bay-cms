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

Route::get('dashboard', 'DashboardController@client_dashboard')->name('dashboard');
Route::get('dashboard/{client_id}', 'DashboardController@client_dashboard');

Route::group(['prefix' => 'components'], function () {
    Route::get('/sidebar', 'API\ComponentsAPIController@get_sidebar');
    Route::get('/sidebar/clients', 'API\ComponentsAPIController@get_clients_sidebar');
    Route::get('/clients/{for}', 'API\ComponentsAPIController@get_clients');
    Route::get('/push-notes/platforms/{client_id}', 'API\ComponentsAPIController@get_push_note_platforms');

});

Route::group(['prefix' => 'users'], function () {
    Route::group(['prefix' => 'dashboard'], function () {
        Route::group(['prefix' => 'widgets'], function () {
            Route::get('/info-box-grid/{client}', 'API\DashboardAPIController@get_info_box_grid_data');
            Route::get('/pie-chart/{client}', 'API\DashboardAPIController@get_pie_chart_data');
        });
    });

    Route::get('/push-notes/{client_id}/{platform_id}', 'API\PushNotesAPIController@get_push_notes_users');
    Route::post('/push-notes/fire', 'API\PushNotesAPIController@fire');
});

