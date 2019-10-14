<?php

namespace App\Actions\Sidebar;

use Bouncer;
use App\Clients;
use App\Repositories\MenuOptionsRepository;

class GetClientsSideBarOptions
{
    protected $clients, $repo;

    public function __construct(MenuOptionsRepository $repo, Clients $clients)
    {
        $this->repo = $repo;
        $this->clients = $clients;
    }

    public function execute()
    {
        $menu_options = [];

        $user = backpack_user();
        $clients = $this->clients->all()->where('active', '=', 1);

        if(count($clients) > 0)
        {
            foreach ($clients as $client)
            {
                if((!Bouncer::is(backpack_user())->a('client')) || ($user->can('access-client', $client)))
                {
                    $menu_options[] = [
                        'client_id' => $client->id,
                        'name' => $client->name
                    ];

                }
            }
        }

        return $menu_options;
    }
}
