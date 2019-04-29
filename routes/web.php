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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/settings', 'SettingsController@index')->name('settings');
Route::get('/batman', 'SettingsController@admin_menu')->name('admin-settings');

Route::get('/reports', 'ReportingController@index')->name('reporting');
Route::get('/live-tracking', 'ReportingController@tracking_menu')->name('live-tracking');


