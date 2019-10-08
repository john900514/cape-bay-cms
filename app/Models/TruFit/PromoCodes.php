<?php

namespace App\ExternalModels\TruFit\mySQL;

use App\Traits\UuidModel;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromoCodes extends Model
{
    use CrudTrait, LogsActivity, SoftDeletes, UuidModel;

    protected $table = 'membership_promos';
    protected $primaryKey = 'id';
    protected $connection = 'trufit-main-api-mysql';

    public function amenities()
    {
        return $this->hasMany('App\ExternalModels\TruFit\mySQL\PromoAmenities','promo_id','id');
    }
}
