<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserMgntService;
use App\Services\ClientMgntService;


class ClientSupportController extends Controller
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
        $args = [
            'module' => 'default'
        ];

        $clients = $this->client_svc->getAllClients();
        $final_clients = [];


        $args['clients'] = array_merge(['Select a Client'], $final_clients);
        $user = $this->user_svc->getUserRecordAndRole();
        $args['menu_options'] = $this->user_svc->getDashMenuOptions($user['roles']);

        return view('csupport.supportdash', $args);
    }
}
