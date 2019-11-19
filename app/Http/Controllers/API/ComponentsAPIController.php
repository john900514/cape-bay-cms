<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Dropdowns\GetClientsOptions;
use App\Actions\Sidebar\GetSideBarMenuOptions;
use App\Actions\Sidebar\GetClientsSideBarOptions;
use App\Actions\Sidebar\GetClientsSideBarContextOptions;
use App\Actions\Dropdowns\GetPushPlatformsOptions;

class ComponentsAPIController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get_sidebar(GetSideBarMenuOptions $action)
    {
        $results = $action->execute();

        return response()->json($results);
    }

    public function get_clients_context_sidebar(GetClientsSideBarContextOptions $action, $client_id)
    {
        $results = $action->execute($client_id);

        return response()->json($results);
    }

    public function get_clients_sidebar(GetClientsSideBarOptions $action)
    {
        $results = $action->execute();

        return response()->json($results);
    }

    public function get_clients(string $for, GetClientsOptions $action)
    {
        $results = $action->execute($for);

        return response()->json($results);
    }

    public function get_push_note_platforms(int $client_id, GetPushPlatformsOptions $action)
    {
        $results = $action->execute($client_id, 'push-notes');

        return response()->json($results);
    }
}
