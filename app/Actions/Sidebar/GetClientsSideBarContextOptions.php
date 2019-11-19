<?php

namespace App\Actions\Sidebar;

use App\Repositories\MenuOptionsRepository;

class GetClientsSideBarContextOptions
{
    protected $repo;

    public function __construct(MenuOptionsRepository $repo)
    {
        $this->repo = $repo;
    }

    public function execute($client_id)
    {
        $user = backpack_user();
        $roles = $user->getRoles()->toArray();

        $menu_options = $this->repo->getContextMenuOptions('dash', $roles, $client_id);

        return $menu_options;
    }
}
