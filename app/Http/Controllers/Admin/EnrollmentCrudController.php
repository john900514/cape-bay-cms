<?php

namespace App\Http\Controllers\Admin;

use Bouncer;
use App\Clients;
use App\Repositories\ClientServiceRepository;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\EnrollmentRequest as StoreRequest;
use App\Http\Requests\EnrollmentRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class EnrollmentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class EnrollmentCrudController extends CrudController
{
    protected $clients, $client_svc_repo;

    public function __construct(Clients $clients, ClientServiceRepository $c_repo)
    {
        parent::__construct();
        $this->clients = $clients;
        $this->client_svc_repo = $c_repo;
    }

    public function setup()
    {
        $req_uri = explode('/', $this->crud->request->getRequestUri());
        $last_pos = 2;
        $client_id = $req_uri[$last_pos];
        $client = $this->clients->find(intval($client_id));

        $this->crud->setRoute(config('backpack.base.route_prefix') . "/{$client_id}/enrollments");
        $this->crud->setEntityNameStrings('enrollment', 'enrollments');

        $can_report = $client->abilities()->get()->where('name', '=', 'report-enrollments')->first();

        if(!is_null($can_report) > 0)
        {
            if((!Bouncer::is(backpack_user())->a('client')) || (backpack_user()->can('access-client', $client)))
            {
                /*
                 |--------------------------------------------------------------------------
                 | CrudPanel Basic Information
                 |--------------------------------------------------------------------------
                */
                $model_name = $this->client_svc_repo->getEnrollmentCrudModel($client_id);
                if($model_name)
                {
                    $this->crud->setModel($model_name);
                    $this->crud->with('lead');

                    /*
                    |--------------------------------------------------------------------------
                    | CrudPanel Configuration
                    |--------------------------------------------------------------------------
                    */

                    // TODO: remove setFromDb() and manually define Fields and Columns
                    //$this->crud->setFromDb();

                    $column_defs = $this->client_svc_repo->getEnrollmentFieldDefs($client_id);
                    $this->crud->addColumns($column_defs);
                    $this->crud->denyAccess('update');

                    // add asterisk for fields that are required in EnrollmentRequest
                    $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
                    $this->crud->setRequiredFields(StoreRequest::class, 'create');
                    $this->data['can_report'] = true;
                }
                else
                {
                    $this->data['can_report'] = false;
                }
            }
            else
            {
                $this->data['can_report'] = false;
            }
        }
        else
        {
            $this->data['can_report'] = false;
        }

        // get dependency data
        $this->data['client_id'] = $client_id;
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
