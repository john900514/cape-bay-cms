<?php

namespace App\Models\TruFit;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class WalletPasses extends Model
{
    use LogsActivity, SoftDeletes, UuidModel;

    protected $connection = 'trufit-mobile-app-mysql';

    protected $fillable = ['app_user_id','pass_identifier', 'pass_type'];

    public function getPassViaIdentity($pass_identifier, $pass_type)
    {
        $results = false;

        $record = $this->where('pass_identifier', '=', $pass_identifier)
            ->where('pass_type', '=', $pass_type)
            ->first();

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }

    public function getPassViaUserAndSerial($pass_identifier, $user_id)
    {
        $results = false;

        $record = $this->where('pass_identifier', '=', $pass_identifier)
            ->where('app_user_id', '=', $user_id)
            ->first();

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }
}
