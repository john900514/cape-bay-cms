<?php

namespace App\Services\Clients;

use App\Models\TruFit\AppUsers;

class TruFitClientService
{
    protected $app_users;

    public function __construct(AppUsers $app_users)
    {
        $this->app_users = $app_users;
    }

    public function getMobileUsers()
    {
        $result = false;

        $users = $this->app_users->whereNotNull('expo_push_token')
            //->limit(100)
            ->get();

        if(count($users) > 0)
        {
            $prog = [];
            foreach($users as $user)
            {
                $user->last_login = date('Y-m-d m:i:s', $user->last_login);
                $prog[] = $user->toArray();

            }
            // @todo - any any of necessary data
            $result = [
                'note' => 'mobile',
                'users' => $prog
            ];
        }

        return $result;
    }

    public function getWalletUsers()
    {
        $result = false;

        /**
         * STEPS
         * @todo - import the config DB connection
         * 1. Get all the undeleted device_pass_registrations
         * 2. foreach reg, get the device, the wallet and the user associated.
         * 3. store that data in results and move on
         */

        return $result;
    }
}
