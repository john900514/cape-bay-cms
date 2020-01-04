<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/clients/{client_uuid}/leads', 'API\ClientLeadsAPIController@post_lead');

Route::get('/clients/{client_uuid}/budgets', 'API\ClientAdBudgetsAPIController@get_all_budget_data');
Route::get('/clients/{client_uuid}/budgets/market/{name}', 'API\ClientAdBudgetsAPIController@get_budget_data_for_market');
Route::get('/clients/{client_uuid}/budgets/club/{club_id}', 'API\ClientAdBudgetsAPIController@get_budget_data_for_club');
