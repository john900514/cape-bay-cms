<?php

namespace App\ExternalModels\TruFit\mySQL;

use App\Traits\UuidModel;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PromoAmenities extends Model
{
    use CrudTrait, LogsActivity, UuidModel;

    protected $table = 'promo_amenities';
    protected $primaryKey = 'id';
    protected $connection = 'tfmysql';
    protected $fillable = ['amenity'];

    public function promo()
    {
        return $this->hasOne('App\ExternalModels\TruFit\mySQL\PromoCodes', 'id', 'promo_id');
    }
}
