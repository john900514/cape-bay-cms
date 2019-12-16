<?php

namespace App\Models\TruFit;

use Illuminate\Database\Eloquent\Model;

class PushNotifications extends Model
{
    protected $table = 'push_notifications';
    public $timestamps = false;
    protected $connection = 'trufit-mobile-app-pgsql';
}
