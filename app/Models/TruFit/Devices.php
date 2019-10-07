<?php

namespace App\Models\TruFit;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Devices extends Model
{
    use LogsActivity, Notifiable, SoftDeletes, UuidModel;

    protected $connection = 'trufit-mobile-app-mysql';

    protected $fillable = ['device_library_id', 'push_token'];

    public function passes()
    {
        return $this->hasManyThrough('App\Models\TruFit\WalletPasses',
            'App\DevicePassRegs', 'device_id', 'uuid',
            'uuid', 'pass_id'
        );
    }

    public function registrations()
    {
        return $this->hasMany('App\Models\TruFit\DevicePassRegs', 'device_id', 'uuid');
    }

    public function routeNotificationForApn()
    {
        return $this->push_token;
    }
}

