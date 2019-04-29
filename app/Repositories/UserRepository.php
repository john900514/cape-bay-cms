<?php

namespace App\Repositories;

use App\User;

class UserRepository
{
    protected $user_model;

    public function __construct(User $model)
    {
        $this->user_model = new BaseRepository($model);
    }
}
