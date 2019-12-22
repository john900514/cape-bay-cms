<?php

namespace App\Http\Controllers\API;

use App\Clients;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientLeadsAPIController extends Controller
{
    protected $client_model, $request;

    public function __construct(Request $request, Clients $clients)
    {
        $this->request = $request;
        $this->client_model = $clients;
    }

    public function post_lead($client)
    {
        $results = ['success' => false, 'reason' => 'Invalid Request'];
        $status = 401;

        $client = $this->client_model->whereUuid($client)->first();

        if(!is_null($client))
        {
            $data = $this->request->all();

            $log_name = str_replace(' ', '-', strtolower($client->name));
            activity($log_name.'-lead-capture')
                ->causedBy($client)
                ->withProperties($data)
                ->log('Posting Lead for '.$client->name);
            $results = ['success' => true];
            $status = 200;
        }

        return response(json_encode($results), $status);
    }
}
