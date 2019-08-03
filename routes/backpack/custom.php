<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    CRUD::resource('clients', 'ClientsCrudController');

    Route::get('/repo', 'RepoController@index')->name('home');
    Route::get('/repo/{client_uuid}', 'RepoController@get_client_data_stores')->name('home');
    Route::get('/repo/trufit/amenities', 'RepoController@get_client_data_store')->name('home');

}); // this should be the absolute last line of this file

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers',
],
    function () { // custom admin routes
    Route::get('/settings', 'SettingsController@index')->name('settings');
    Route::get('/dashboard', 'HomeController@index')->name('home');

    Route::group(['prefix' => 'reporting'], function () {
        Route::get('/', 'ReportingController@index')->name('reporting');
    });

    Route::group(['prefix' => 'reports'], function () {
        Route::get('/{uuid}', 'ReportingController@get_client_reports')->name('get-client-reports');
        //Route::get('/{uuid}/{report}', 'ReportingController@get_client_report')->name('get-client-report');

        CRUD::resource('/trufit/payment-leads', 'Reporting\TruFit\LeadsCrudController');
        CRUD::resource('/trufit/payment-conversions', 'Reporting\TruFit\ConversionsCrudController');
        CRUD::resource('/trufit/referral-leads', 'Reporting\TruFit\ReferralCrudController');

        CRUD::resource('/fitness360/facebook-leads', 'Reporting\Fitness360\FBLeadsCrudController');
        CRUD::resource('/morning/payment-leads', 'Reporting\Morning\LeadsCrudController');
        CRUD::resource('/morning/payment-conversions', 'Reporting\Morning\ConversionsCrudController');
    });

    Route::group(['prefix' => 'live-tracking'], function () {
        Route::get('/', 'ReportingController@tracking_menu')->name('live-tracking');
        Route::get('/{uuid}', 'ReportingController@get_client_trackers')->name('get-client-trackers');
        Route::get('/{uuid}/{tracker}', 'ReportingController@get_client_tracker')->name('get-client-tracker');
    });

    Route::group(['prefix' => 'csupport'], function () {
        Route::get('/', 'ClientSupportController@index')->name('client-support');
    });

    Route::get('edit-account-info', 'Auth\MyAccountController@getAccountInfoForm')->name('backpack.account.info');
    Route::post('edit-account-info', 'Auth\MyAccountController@postAccountInfoForm');
    Route::get('change-password', 'Auth\MyAccountController@getChangePasswordForm')->name('backpack.account.password');
    Route::post('change-password', 'Auth\MyAccountController@postChangePasswordForm');

    CRUD::resource('repo/trufit/amenities/view', 'DataRepos\TruFit\AmenitiesCrudController');

    Route::group(['prefix' => 'messaging'], function () {
        Route::get('/', 'MessagingController@index')->name('push-notifications');
        Route::get('/{app_id}', 'MessagingController@manage');
        Route::post('/push/fire', 'MessagingController@firePushNotes');
    });
});
