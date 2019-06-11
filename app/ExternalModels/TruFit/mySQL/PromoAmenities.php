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
    protected $fillable = ['amenity', 'promo_id'];

    public function getPlanNamesFromClubCode($club_id, $promo_id)
    {
        $results = [];

        $records = $this->select('membership_promos.id', 'membership_promos.Description')
            ->where('promo_amenities.clubId','=', $club_id)
            ->where('membership_promos.PromoCode','=', $promo_id)
            ->join('membership_promos', 'membership_promos.id', 'promo_amenities.promo_id')
            ->get();

        if(count($records) > 0)
        {
            $codedescs = [];

            foreach ($records as $record)
            {
                if(!array_key_exists($record->id, $codedescs))
                {
                    $codedescs[$record->id] = $record->Description;
                }
            }

            $results = $codedescs;
        }

        return $results;
    }

    public function promo()
    {
        return $this->hasOne('App\ExternalModels\TruFit\mySQL\PromoCodes', 'id', 'promo_id');
    }
}
