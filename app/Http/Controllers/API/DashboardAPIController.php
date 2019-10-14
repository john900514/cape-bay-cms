<?php

namespace App\Http\Controllers\API;

use App\Repositories\ClientServiceRepository;
use Bouncer;
use App\Clients;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardAPIController extends Controller
{
    protected $clients, $client_svc_repo, $request;

    public function __construct(Request $request, Clients $clients, ClientServiceRepository $c_repo)
    {
        $this->request = $request;
        $this->clients = $clients;
        $this->client_svc_repo = $c_repo;
    }

    public function get_info_box_grid_data(string $client)
    {
        $results = ['success' => false, 'reason'=> 'Unauthorized'];

        $user = backpack_user();

        if(!is_null($user))
        {
            $client = $this->clients->find(intval($client));
            if((!Bouncer::is($user)->a('client')) || ($user->can('access-client', $client)))
            {
                $widgets = $client->widgets()->where('widget_type', '=', 'info-box')->get();
                // @todo - get user_widget_overrides

                if(count($widgets) > 0)
                {
                    $boxes = [];
                    foreach($widgets as $widget)
                    {
                        $service = $this->client_svc_repo->getService($widget->service);

                        // @todo - get props if necessary
                        $box = $service->{$widget->function}();

                        if(count($box) > 0)
                        {
                            $boxes[] = $box;
                        }
                    }

                    $results = ['success' => true, 'data' => $boxes];
                }
            }
        }

        return response()->json($results);
    }

    public function get_pie_chart_data(string $client)
    {
        $results = ['success' => false, 'reason'=> 'Unauthorized'];

        $user = backpack_user();

        if(!is_null($user))
        {
            $client = $this->clients->find(intval($client));
            if((!Bouncer::is($user)->a('client')) || ($user->can('access-client', $client)))
            {
                $widget = $client->widgets()->where('widget_type', '=', 'pie-chart')->first();
                // @todo - get user_widget_overrides

                if(!is_null($widget))
                {
                    $service = $this->client_svc_repo->getService($widget->service);

                    // @todo - get props if necessary
                    $box = $service->{$widget->function}();

                    // @todo - get list of other pie charts available to the user
                    // @todo - if client is 1 get all pie chart widgets
                    // @todo - else scope the client id.

                    if(count($box) > 0)
                    {
                        $results = ['success' => true, 'data' => $box];
                    }
                    else
                    {
                        $results['reason'] = 'No Pie Chart Widget Data Available';
                    }
                }
            }
        }

        return response()->json($results);
    }
}
