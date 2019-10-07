<?php

namespace App\Services;

use App\ClientFeatures;

class PushNotificationsService
{
    protected $features, $mobile, $wallet;

    public function __construct(ClientFeatures $features,
                                PNWalletPassService $wallet,
                                PNMobileAppService $mobile)
    {
        $this->features = $features;
        $this->mobile = $mobile;
        $this->wallet = $wallet;
    }

    public function getService(int $client_id, int $feature_id)
    {
        $results = false;

        $record = $this->features->where('client_id', '=', $client_id)
            ->where('id', '=', $feature_id)
            ->first();

        if(!is_null($record))
        {
            // * @todo - support other types of push-notes in the future
            switch($record->feature)
            {
                case 'Mobile App':
                    $results = $this->mobile;
                    break;

                case 'Wallet Passes':
                    $results = $this->wallet;
                    break;

                default:
            }
        }

        return $results;
    }
}
