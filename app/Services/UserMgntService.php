<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\MenuOptionsRepository;

class UserMgntService
{
    protected $menu_repo;

    public function __construct(MenuOptionsRepository $menu_repo)
    {
        $this->menu_repo = $menu_repo;
    }


    public function getUserRecordAndRole()
    {
        $results = false;

        $user = Auth::user();

        if($user)
        {
            $roles = $user->getRoles()->toArray();

            if(count($roles) > 0)
            {
                $user = $user->toArray();
                $user['roles'] = array_merge(['any'], $roles);

                $results = $user;
            }
        }

        return $results;
    }

    public function getDashMenuOptions(array $roles)
    {
        return $this->menu_repo->getMenuOptions('dash', $roles);
    }

    public function getLiveTrackMenuOptions(array $roles)
    {
        return $this->menu_repo->getMenuOptions('live-tracking', $roles);
    }
}
