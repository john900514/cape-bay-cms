<?php

namespace App\Models\TAC;

use Illuminate\Database\Eloquent\Model;

class PushNotifications extends Model
{
    protected $table = 'push_notifications';
    public $timestamps = false;
    protected $connection = 'tac-mobile-app-pgsql';
}
