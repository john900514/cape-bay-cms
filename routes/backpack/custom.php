<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.
Route::group(
    [
        'namespace'  => 'App\Http\Controllers',
        'middleware' => 'web',
        'prefix'     => config('backpack.base.route_prefix'),
    ], function () {
    Route::get('dashboard', 'DashboardController@dashboard')->name('backpack.dashboard');
    Route::get('dashboard/{client_id}', 'DashboardController@dashboard');
});


Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::group(['prefix' => 'messaging'],  function() {
        Route::get('/', 'MessagingController@push_notes_index');
    });

}); // this should be the absolute last line of this file
