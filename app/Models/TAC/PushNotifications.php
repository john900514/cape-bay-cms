<?php

namespace App\Models\TAC;

use Illuminate\Database\Eloquent\Model;

class PushNotifications extends Model
{
    protected $table = 'push_notifications';

    protected $connection = 'tac-mobile-app-mysql';
}
