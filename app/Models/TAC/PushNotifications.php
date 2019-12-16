<?php

namespace App\Models\TAC;

use Illuminate\Database\Eloquent\Model;

class PushNotifications extends Model
{
    protected $connection = 'tac-mobile-app-pgsql';

    protected $table = 'push_notifications';

    public $timestamps = false;
}
