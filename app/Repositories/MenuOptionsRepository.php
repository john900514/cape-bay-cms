<?php

namespace App\Repositories;

use App\MenuOptions;

class MenuOptionsRepository
{
    protected $menu_options;

    public function __construct(MenuOptions $model)
    {
        $this->menu_options = new BaseRepository($model);
    }

    public function getMenuOptions($menu_name, array $roles)
    {
        $results = [];

        $records = $this->menu_options->getModel()
        ->where('active', '=', 1)
        ->where('menu_name', '=', $menu_name)
        ->whereIn('permitted_role', $roles)
        ->orderBy('order')
        ->get();

        if(count($records) > 0)
        {
            $results = $records->toArray();
        }
        return $results;
    }
}
