<?php

namespace App\Services\Pixels;

use App\Pixels;
use App\Clients;
use App\PixelActivityLog;

class ProcessPixelData extends PixelService
{
    public function __construct(Pixels $p, PixelActivityLog $pa)
    {
        parent::__construct($p, $pa);
    }

    public function execute(array $data, Clients $client)
    {
        $results = false;

        //Check the pixels DB for the pixel ID with the associated client id
        //     or create it
        if(array_key_exists('pixel_id', $data))
        {
            $pixel = $this->getPixelModel()->where('pixel_id', '=', $data['pixel_id'])->first();
            if(is_null($pixel))
            {
                $pixel_mod = $this->getPixelModel();
                $pixel = new $pixel_mod();
                $pixel->pixel_id = $data['pixel_id'];
                $pixel->client_id = $client->id;
                $pixel->save();
            }

            foreach ($data as $attribute => $value)
            {
                switch($attribute)
                {
                    case 'pageName':
                        // Cut a record with in pixel activity with the page name
                        $aco = $this->getPixelActivityModel();
                        $activity = new $aco();
                        $activity->pixel_id = $pixel->pixel_id;
                        $activity->activity = 'Arrived at '.$value;
                        $activity->value = $value;
                        $activity->misc = json_encode($data);
                        $activity->save();

                        // Cut an activity log log
                        activity('pixel-log')
                            ->causedBy($pixel)
                            ->performedOn($activity)
                            ->withProperties($data)
                            ->log("pixel tracked {$attribute} event");
                        break;

                    case 'promocode':
                        // Cut a record with in pixel activity with the page name
                        $aco = $this->getPixelActivityModel();
                        $activity = new $aco();
                        $activity->pixel_id = $pixel->pixel_id;
                        $activity->activity = 'PromoCode Selected '.$value;
                        $activity->value = $value;
                        $activity->misc = json_encode($data);
                        $activity->save();

                        // Cut an activity log log
                        activity('pixel-log')
                            ->causedBy($pixel)
                            ->performedOn($activity)
                            ->withProperties($data)
                            ->log("pixel tracked {$attribute} event");
                        break;

                    case 'lead_id':
                        // @todo - perhaps use the client id to get the data module, and thus the actual models for the activity log

                        $aco = $this->getPixelActivityModel();
                        $activity = new $aco();
                        $activity->pixel_id = $pixel->pixel_id;
                        $activity->activity = 'Lead id  '.$value;
                        $activity->value = $value;
                        $activity->misc = json_encode($data);
                        $activity->save();

                        // Cut an activity log log
                        activity('pixel-log')
                            ->causedBy($pixel)
                            ->performedOn($activity)
                            ->withProperties($data)
                            ->log("promo was preloaded.");
                        break;

                    case 'club_id':
                        // Cut a record with in pixel activity with the page name
                        $aco = $this->getPixelActivityModel();
                        $activity = new $aco();
                        $activity->pixel_id = $pixel->pixel_id;
                        $activity->activity = 'Club Selected  '.$value;
                        $activity->value = $value;
                        $activity->misc = json_encode($data);
                        $activity->save();

                        // Cut an activity log log
                        activity('pixel-log')
                            ->causedBy($pixel)
                            ->performedOn($activity)
                            ->withProperties($data)
                            ->log("promo was preloaded.");
                        break;

                    case 'pixel_id':
                        continue;
                        break;

                    default:
                        $aco = $this->getPixelActivityModel();
                        $activity = new $aco();
                        $activity->pixel_id = $pixel->pixel_id;
                        $activity->activity = 'Misc Value -  '.$attribute;
                        $activity->value = $value;
                        $activity->misc = json_encode($data);
                        $activity->save();

                        // Cut an activity log log
                        activity('pixel-log')
                            ->causedBy($pixel)
                            ->performedOn($activity)
                            ->withProperties($data)
                            ->log("promo was preloaded.");
                }
            }

            $results = true;
        }


        return $results;
    }
}
