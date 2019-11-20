<?php

namespace App;

use App\Traits\UuidModel;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class AdBudgets extends Model
{
    use CrudTrait, LogsActivity, SoftDeletes, UuidModel;

    protected $table = 'ad_budgets';
    protected $fillable = ['client_id', 'market_id', 'club_id', 'facebook_ig_budget', 'google_budget'];

    public function client()
    {
        return $this->hasOne('App\Clients', 'id', 'client_id');
    }

    public function market()
    {
        return $this->hasOne('App\AdMarkets', 'id', 'market_id');
    }


}
