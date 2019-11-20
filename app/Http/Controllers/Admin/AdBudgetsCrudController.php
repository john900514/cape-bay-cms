<?php

namespace App\Http\Controllers\Admin;

use App\Services\AdBudgetMgntService;
use Bouncer;
use App\Clients;
use App\Repositories\ClientServiceRepository;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AdBudgetsRequest as StoreRequest;
use App\Http\Requests\AdBudgetsRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class AdBudgetsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AdBudgetsCrudController extends CrudController
{
    protected $ad_svc, $clients, $client_svc_repo;

    public function __construct(Clients $clients, ClientServiceRepository $c_repo, AdBudgetMgntService $ad_svc)
    {
        parent::__construct();
        $this->clients = $clients;
        $this->client_svc_repo = $c_repo;
        $this->ad_svc = $ad_svc;
    }

    public function setup()
    {
        $req_uri = explode('/', $this->crud->request->getRequestUri());
        $last_pos = 2;
        $client_id = $req_uri[$last_pos];
        $client = $this->clients->find(intval($client_id));

        if(Bouncer::is(backpack_user())->a('client'))
        {
            $this->crud->setRoute( "features/{$client_id}/budgets");
        }
        else
        {
            $this->crud->setRoute(config('backpack.base.route_prefix') . "/{$client_id}/budgets");
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\AdBudgets');
        $this->crud->addClause('where', 'client_id', '=', $client_id);

        $this->crud->setEntityNameStrings('Ad Budget', 'Ad Budgets');

        if((!Bouncer::is(backpack_user())->a('client')) || (backpack_user()->can('access-client', $client)))
        {
            /*
            |--------------------------------------------------------------------------
            | CrudPanel Configuration
            |--------------------------------------------------------------------------
            */
            $repo = $this->client_svc_repo;
            $v_market_name = [
                'name' => 'market.market_name', // the db column name (attribute name)
                'label' => "Market Name", // the human-readable label for it
                'type' => 'text', // the kind of column to show
                'entity' => 'market',
                'priority' => 2
            ];
            $v_club_name = [
                'name' => 'club_id',
                'label' => 'Club',
                'type' => 'closure',
                'function' => function($entry) use($repo, $client_id) {
                    $results = false;

                    $name = $repo->getClubName($client_id, $entry->club_id);

                    if($name)
                    {
                        $results = $name;
                    }

                    return $results;
                },
                'priority' => 1
            ];
            $v_facebook_budget = [
                'name' => 'facebook_ig_budget', // the db column name (attribute name)
                'label' => "Facebook/IG Budget", // the human-readable label for it
                'type' => 'closure',
                'function' => function($entry) {
                    $results = 'No Budget Set';

                    if(!is_null($entry->facebook_ig_budget))
                    {
                        $results = "$".$entry->facebook_ig_budget;
                    }

                    return $results;
                },
                'priority' => 3
            ];
            $v_google_budget = [
                'name' => 'google_budget', // the db column name (attribute name)
                'label' => "Google Budget", // the human-readable label for it
                'type' => 'closure',
                'function' => function($entry) {
                    $results = 'No Budget Set';

                    if(!is_null($entry->google_budget))
                    {
                        $results = "$".$entry->google_budget;
                    }

                    return $results;
                },
                'priority' => 3
            ];

            $column_defs = [$v_market_name, $v_club_name, $v_facebook_budget, $v_google_budget];
            $this->crud->addColumns($column_defs);

            $cu_client_id = [   // Hidden
                'name' => 'client_id',
                'type' => 'hidden',
                'value' => $client_id,
            ];

            $cu_market_name = [
                'name' => 'market_id',  // the db column name (attribute name)
                'label' => "Select Market", // the human-readable label for it
                'type' => 'select_from_array',
                'options' => $this->ad_svc->getMarketCRUDDropdownOptions($client_id)
            ];

            $cu_club_name = [
                'name' => 'club_id',  // the db column name (attribute name)
                'label' => "Select Club", // the human-readable label for it
                'type' => 'select_from_array',
                'options' => $this->client_svc_repo->getClubDropdown($client_id)
            ];

            $cu_fb_budget = [
                'type' => 'text',
                'name' => 'facebook_ig_budget',
                'label' => 'Facebook/Instagram Budget',
                'priority' => 1
            ];

            $cu_google_budget = [
                'type' => 'text',
                'name' => 'google_budget',
                'label' => 'Google Budget',
                'priority' => 1
            ];

            $both_defs = [$cu_client_id, $cu_market_name, $cu_club_name, $cu_fb_budget, $cu_google_budget];
            $this->crud->addFields($both_defs,'both');

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
