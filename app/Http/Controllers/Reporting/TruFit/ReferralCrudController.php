<?php

namespace App\Http\Controllers\Reporting\TruFit;

use App\Services\UserMgntService;
use App\Http\Requests\ClientsRequest as StoreRequest;
use App\Http\Requests\ClientsRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class ReferralCrudController extends CrudController
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
        $this->crud->setModel('App\ExternalModels\TruFit\mySQL\Referrals');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/reports/{uuid}/referral-leads');
        $this->crud->setEntityNameStrings('referral', 'referrals');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();

        $this->crud->setColumns([
            [
                'name' => 'campaign', // the db column name (attribute name)
                'label' => 'Ref Campaign', // the human-readable label for it
                'type' => 'text' // the kind of column to show
            ],
            [
                'name' => 'club', // the db column name (attribute name)
                'label' => 'Club', // the human-readable label for it
                'type' => 'text' // the kind of column to show
            ],
            [
                'name' => 'first_name', // the db column name (attribute name)
                'label' => 'First', // the human-readable label for it
                'type' => 'text' // the kind of column to show
            ],
            [
                'name' => 'last_name', // the db column name (attribute name)
                'label' => 'Last', // the human-readable label for it
                'type' => 'text' // the kind of column to show
            ],
            [
                'name' => 'email', // the db column name (attribute name)
                'label' => 'Email', // the human-readable label for it
                'type' => 'text' // the kind of column to show
            ],
            [
                'name' => 'mobile', // the db column name (attribute name)
                'label' => 'Phone', // the human-readable label for it
                'type' => 'text' // the kind of column to show
            ],
            [
                'name' => 'referral_name', // the db column name (attribute name)
                'label' => 'Referred By', // the human-readable label for it
                'type' => 'text' // the kind of column to show
            ],
        ]);

        // add asterisk for fields that are required in ClientsRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->removeColumn('uuid');

        $req_vars = $this->request->all();

        $filt_args2 = [ // add a "simple" filter called Draft
            'type' => 'dropdown',
            'name' => 'campaign',
            'label'=> 'Ref Campaign'
        ];

        $valus = [
            0 => 'None',
            'buddy' => 'buddy',
            'combo6' => 'combo6',
            'summer' => 'summer',
        ];

        $crus = $this->crud;

        $callback = function($filter) use ($crus) { // if the filter is active (the GET parameter "draft" exits)
            $crus->addClause('where','campaign', $filter);
        };

        if(array_key_exists('campaign', $req_vars))
        {
            $this->crud->addFilter($filt_args2, $valus, $callback($req_vars['campaign']));
        }

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
