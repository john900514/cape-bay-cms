<?php

namespace App\Models\TruFit;

use App\Traits\UuidModel;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referrals extends Model
{
    use CrudTrait, LogsActivity, SoftDeletes, UuidModel;

    protected $table = 'referrals';
    protected $primaryKey = 'id';
    protected $connection = 'trufit-main-api-mysql';

    protected static $logAttributes = ['uuid','referral_name', 'first_name', 'last_name', 'email', 'mobile', 'club'];

    protected $fillable = ['referral_name', 'first_name', 'last_name', 'email', 'mobile', 'club', 'campaign'];

    public function insert($data)
    {
        $results = false;

        $model = new $this;
        $model->first_name   = $data['first_name'];
        $model->last_name    = $data['last_name'];
        $model->email        = $data['email'];
        $model->mobile = $data['mobile'];

        $model->club = $data['club'];
        $model->referral_name = $data['referring_name'];

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }

    public function getReferralBreakDown()
    {
        $results = false;

        $report = $this->select('campaign', DB::raw('count(campaign) as total'))
            ->groupBy('campaign')
            ->get();

        if(count($report) > 0)
        {
            $results = $report->toArray();
        }

        return $results;
    }
}
