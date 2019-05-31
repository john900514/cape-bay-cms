<?php

namespace App\Http\Controllers\Reporting\TruFit;

use App\Services\UserMgntService;
use App\Http\Requests\ClientsRequest as UpdateRequest;
use App\Http\Requests\ClientsRequest as StoreRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class ConversionsCrudController extends CrudController
{
    public function __construct(UserMgntService $user_svc)
    {
        parent::__construct();
        $this->user_svc = $user_svc;
    }

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\ExternalModels\TruFit\mySQL\Conversions');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/reports/{uuid}/payment-conversions');
        $this->crud->setEntityNameStrings('conversion', 'conversions');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in ClientsRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->removeColumn('uuid');

        $user = $this->user_svc->getUserRecordAndRole();
        $this->data['menu_options'] = $this->user_svc->getDashMenuOptions($user['roles']);
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
