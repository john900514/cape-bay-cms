<?php

namespace App\Http\Controllers;

use Bouncer;
use App\Clients;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $clients, $request;

    public function __construct(Request $request, Clients $clients)
    {
        $this->request = $request;
        $this->clients = $clients;
    }

    public function dashboard(string $client_id = '1')
    {
        $props = [
            'client_id' => 1,
            'default_id' => 1
        ];

        if(Bouncer::is(backpack_user())->a('client'))
        {
            return redirect('/dashboard');
        }
        else
        {
            if($client_id != '1')
            {
                $props['client_id'] = $client_id;
            }
        }

        return view('anchor.home.dashboard', $props);
    }

    public function client_dashboard(string $client_id = null)
    {
        if(Bouncer::is(backpack_user())->a('client'))
        {
            $props = [
                'client_id' => '',
                'default_id' => ''
            ];

            $abilities = backpack_user()->getAbilities()
                ->where('name', '=', 'access-client');

            if(count($abilities) > 0)
            {
                $props['client_id'] = $abilities[0]->entity_id;
                $props['default_id'] = $abilities[0]->entity_id;

                if(!is_null($client_id) && ($client_id != $props['default_id']))
                {
                    if(backpack_user()->can('access-client', $this->clients->find(intval($client_id))))
                    {
                        $props['client_id'] = $client_id;
                    }
                    else
                    {
                        return redirect('/dashboard');
                    }
                }
            }
            else
            {
                return view('errors.403');
            }
        }
        else {
            return redirect('/cms/dashboard');
        }
        return view('anchor.home.dashboard', $props);
    }
}
