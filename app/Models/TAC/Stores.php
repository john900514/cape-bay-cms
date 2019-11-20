<?php

namespace App\Models\TAC;

use Bouncer;
use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Silber\Bouncer\Database\Concerns\HasRoles;

class Stores extends Model
{
    use LogsActivity, SoftDeletes, UuidModel;

    protected $table = 'stores';
    protected $primaryKey = 'id';
    protected $connection = 'tac-main-api-mysql';

    public function getGroupedClubsForSelect($context = '')
    {
        $results = [];

        $records = $this->all();

        if(count($records) > 0)
        {
            foreach($records as $club)
            {
                if(($context == '') || Bouncer::is($club)->a($context.'-club'))
                {
                    switch($club->State) {
                        case 'NE':
                            $state = 'Nebraska';
                            break;

                        case 'KS':
                            $state = 'Kansas';
                            break;

                        case 'IA':
                            $state = 'Iowa';
                            break;

                        default:
                            $state = 'Unknown';
                    }

                    if(!array_key_exists($state, $results))
                    {
                        $results[$state] = [];
                    }

                    $results[$state][] = ['ClubName' => $club->ClubName, 'ClubId' => $club->ClubId];
                }
            }
        }

        return $results;
    }
}
