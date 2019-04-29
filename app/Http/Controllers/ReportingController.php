<?php

namespace App\Http\Controllers;

use App\Services\ClientMgntService;
use Illuminate\Http\Request;
use App\Services\UserMgntService;

class ReportingController extends Controller
{
    protected $request, $user_svc, $client_svc;

    public function __construct(Request $request, UserMgntService $user_svc, ClientMgntService $client_svc)
    {
        $this->request = $request;
        $this->user_svc = $user_svc;
        $this->client_svc = $client_svc;
    }

    public function index()
    {
        $args = [];

        $clients = $this->client_svc->getAllClients();
        $final_clients = [];
        foreach($clients as $client)
        {
            $final_clients[$client['uuid']] = $client['name'];
        }

        $args['clients'] = array_merge(['Select a Client'], $final_clients);

        return view('reporting.reportsdash', $args);
    }

    public function tracking_menu()
    {
        $args = [];

        $clients = $this->client_svc->getAllClients(); // Get a list of all the clients the user can access;

        $final_clients = [];
        foreach($clients as $client)
        {
            // check client features to see if the client supports any feature inside menu options
            $features = $this->client_svc->getAllFeaturesForClient('live-tracking', $client['id']);

            if(count($features) > 0)
            {
                $final_clients[$client['uuid']] = $client['name'];
            }
        }

        $args['clients'] = array_merge(['Please Select a Client'], $final_clients);

        return view('reporting.tracking', $args);

        //$menu_options = $this->user_svc->getLiveTrackMenuOptions($user['roles']); // Get a list of all Live Track Options
    }
}
