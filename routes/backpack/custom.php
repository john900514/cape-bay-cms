<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web'],
    'namespace'  => 'AnchorCMS\Http\Controllers',
], function () { // custom admin routes
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('backpack.auth.login');
});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'AnchorCMS\Http\Controllers',
], function () { // custom admin routes

    Route::get('/dashboard', 'Admin\DashboardController@index');
    Route::get('/abilities', 'Admin\InternalAdminJSONController@abilities');
    Route::get('/abilities/{role}', 'Admin\InternalAdminJSONController@role_abilities');

    CRUD::resource('crud-users', 'Admin\UsersCrudController');
    CRUD::resource('crud-roles', 'Admin\RolesCrudController');
    CRUD::resource('crud-abilities', 'Admin\AbilitiesCrudController');
}); // this should be the absolute last line of this file
