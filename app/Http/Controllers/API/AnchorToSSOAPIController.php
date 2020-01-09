<?php

namespace App\Http\Controllers\API;

use Bouncer;
use App\User;
use App\Clients;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnchorToSSOAPIController extends Controller
{
    protected $clients, $request, $user_model;

    public function __construct(Request $request, User $user_model, Clients $clients)
    {
        $this->request = $request;
        $this->user_model = $user_model;
        $this->clients = $clients;
    }

    public function verify()
    {
        $results = ['success' => false, 'reason' => 'Invalid Request'];

        $data = $this->request->all();

        // Verify the user or fail.
        if(array_key_exists('user', $data))
        {
            $user = $this->user_model->whereUuid($data['user'])->first();

            if(!is_null($user))
            {
                // Verify the client or fail
                if(array_key_exists('client', $data))
                {

                    $client = $this->clients->whereUuid($data['client'])->first();
                    if(!is_null($client))
                    {
                        // Curate the response
                        $results = [
                            'success' => true,
                            'user' => [
                                'email' => $user->email,
                                'role' => Bouncer::is($user)->a('god') ? 'god':'admin'
                            ]
                        ];
                    }
                    else
                    {
                        $results['reason'] = 'Invalid Client';
                    }
                }
                else
                {
                    $results['reason'] = 'Missing Client';
                }
            }
            else
            {
                $results['reason'] = 'Invalid User';
            }
        }

        return response()->json($results);
    }
}
