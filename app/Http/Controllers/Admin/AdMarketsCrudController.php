<?php

namespace App\Http\Controllers\Admin;

use Bouncer;
use App\Clients;
use App\Repositories\ClientServiceRepository;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BasicRequest as StoreRequest;
use App\Http\Requests\BasicRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class AdBudgetsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AdMarketsCrudController extends CrudController
{
    protected $ad_svc, $clients, $client_svc_repo;

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
            $this->crud->setRoute( "features/{$client_id}/ad-markets");
        }
        else
        {
            $this->crud->setRoute(config('backpack.base.route_prefix') . "/{$client_id}/ad-markets");
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\AdMarkets');
        $this->crud->addClause('where', 'client_id', '=', $client_id);

        $this->crud->setEntityNameStrings('Ad Market', 'Ad Market');

        if((!Bouncer::is(backpack_user())->a('client')) || (backpack_user()->can('access-client', $client)))
        {
            /*
            |--------------------------------------------------------------------------
            | CrudPanel Configuration
            |--------------------------------------------------------------------------
            */
            $market = [
                'type' => 'text',
                'label' => 'Market Name',
                'name' => 'market_name'
            ];

            $this->crud->addColumns([$market]);

            // add asterisk for fields that are required in AdBudgetsRequest
            $this->crud->setRequiredFields(StoreRequest::class, 'create');
            $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
            $this->data['can_report'] = true;
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
