<?php

namespace App\Http\Controllers\Admin;

use Bouncer;
use App\Clients;
use App\Repositories\ClientServiceRepository;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AmenityRequest as StoreRequest;
use App\Http\Requests\AmenityRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class AmenityCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AmenityCrudController extends CrudController
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

        if(Bouncer::is(backpack_user())->a('client'))
        {
            $this->crud->setRoute( "features/{$client_id}/amenities");
        }
        else
        {
            $this->crud->setRoute(config('backpack.base.route_prefix') . "/{$client_id}/amenities");
        }

        $this->crud->setEntityNameStrings('amenity', 'amenities');

        $can_adjust = $client->abilities()->get()->where('name', '=', 'has-amenities')->first();

        if(!is_null($can_adjust) > 0)
        {
            if((!Bouncer::is(backpack_user())->a('client')) || (backpack_user()->can('access-client', $client)))
            {
                /*
                |--------------------------------------------------------------------------
                | CrudPanel Basic Information
                |--------------------------------------------------------------------------
                */
                $model_name = $this->client_svc_repo->getAmenitiesCrudModel($client_id);
                if($model_name)
                {
                    $this->crud->setModel($model_name);

                    /*
                    |--------------------------------------------------------------------------
                    | CrudPanel Configuration
                    |--------------------------------------------------------------------------
                    */

                    // TODO: remove setFromDb() and manually define Fields and Columns
                    $this->crud->setFromDb();

                    // add asterisk for fields that are required in AmenityRequest
                    $this->crud->setRequiredFields(StoreRequest::class, 'create');
                    $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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
