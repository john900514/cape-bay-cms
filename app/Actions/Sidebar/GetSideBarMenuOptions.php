<?php

namespace App\Actions\Sidebar;

use App\Repositories\MenuOptionsRepository;

class GetSideBarMenuOptions
{
    protected $repo;

    public function __construct(MenuOptionsRepository $repo)
    {
        $this->repo = $repo;
    }

    public function execute()
    {
        $user = backpack_user();
        $roles = $user->getRoles()->toArray();

        $menu_options = $this->repo->getMenuOptions('dash', $roles);

        return $menu_options;
    }
}
