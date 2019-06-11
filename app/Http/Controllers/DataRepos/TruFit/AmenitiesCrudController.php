<?php

namespace App\Http\Controllers\DataRepos\TruFit;

use Illuminate\Http\Request;
use App\Services\UserMgntService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ClientsRequest as StoreRequest;
use App\Http\Requests\ClientsRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class AmenitiesCrudController extends CrudController
{
    public function __construct(UserMgntService $user_svc, Request $request)
    {
        $this->user_svc = $user_svc;
        parent::__construct();

    }

    public function setup()
    {
        $this->crud->setModel('App\ExternalModels\TruFit\mySQL\PromoAmenities');

        $req_vars = $this->request->all();

        if(array_key_exists('amen', $req_vars)) {
            $amen = $req_vars['amen'];
            $pathsplode = explode('/', $amen);
            $club = $pathsplode[1];
            $promo = $pathsplode[0];

            session()->put('club', $club);
            session()->put('promo', $promo);
        }
        else {
            $club  = session()->get('club');
            $promo = session()->get('promo');
        }

        $this->crud->setRoute(config('backpack.base.route_prefix') . "/repo/trufit/amenities/view");
        $this->crud->setEntityNameStrings('amenity', 'amenities for promo code - '.$promo);

        $this->crud->addColumn([
            'name' => 'clubId',
            'type' => 'text',
            'label' => 'Club',
            'attributes' => [
                'readonly' => 'readonly',
                'disabled' => 'disabled'
            ]
        ]);

        $this->crud->addColumn([
            'name' => 'Description',
            'type' => 'text',
            'label' => 'Plan',
            'attributes' => [
                'readonly' => 'readonly',
                'disabled' => 'disabled'
            ]
        ]);

        $this->crud->addColumn([
            'name' => 'amenity',
            'type' => 'text',
            'label' => 'Amenity'
        ]);

        $this->crud->addClause('select', [
            'promo_amenities.id',
            'promo_amenities.clubId',
            'promo_amenities.amenity',
            'membership_promos.Description',
        ]);
        $this->crud->addClause('where','promo_amenities.clubId','=', $club);
        $this->crud->addClause('where','membership_promos.PromoCode','=', $promo);
        $this->crud->addClause('join', 'membership_promos', 'membership_promos.id', 'promo_amenities.promo_id');
        //$this->crud->with('promo');

        $this->crud->addField([
            'name' => 'amenity',
            'type' => 'text',
            'label' => 'Amenity'
        ]);


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
