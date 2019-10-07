<?php

namespace App\Models\TruFit;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class DevicePassRegs extends Model
{
    use LogsActivity, SoftDeletes, UuidModel;
    protected $table = 'device_pass_registrations';
    protected $connection = 'trufit-mobile-app-mysql';
    protected $fillable = ['pass_id', 'device_id'];

    public function devices()
    {
        return $this->hasMany('App\Models\TruFit\Devices', 'uuid', 'device_id');
    }

    public function wallet_passes()
    {
        return $this->hasMany('App\Models\TruFit\WalletPasses', 'uuid', 'pass_id');
    }
}
