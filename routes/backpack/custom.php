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
    Route::get('edit-account-info', 'Auth\AccountController@getAccountInfoForm')->name('backpack.account.info');
    Route::get('change-password', 'Auth\AccountController@getChangePasswordForm')->name('backpack.account.password');
});


Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::group(['prefix' => 'messaging'],  function() {
        Route::get('/', 'MessagingController@push_notes_index');
    });

    CRUD::resource('/{client_id}/enrollments', 'EnrollmentCrudController');
    CRUD::resource('/{client_id}/amenities', 'AmenityCrudController');
    CRUD::resource('/{client_id}/pixels', 'PixelCrudController');

}); // this should be the absolute last line of this file
