<?php

namespace App\Services\Clients;

use App\Models\TruFit\Leads;
use App\Models\TruFit\AppUsers;
use App\Models\TruFit\Referrals;
use Illuminate\Support\Facades\DB;
use App\Models\TruFit\Conversions;

class TruFitClientService
{
    protected $app_users, $conversions, $leads, $referrals;

    public function __construct(AppUsers $app_users,
                                Leads $leads,
                                Referrals $referrals,
                                Conversions $conversions)
    {
        $this->leads = $leads;
        $this->app_users = $app_users;
        $this->referrals = $referrals;
        $this->conversions = $conversions;
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

    public function getTotalSales()
    {
        $results = [];

        // @todo - fine tune this query to be more accurate
        $records = $this->conversions->select(DB::raw('count(*) as total'))->first();

        if(!is_null($records))
        {
            $results = [
                'class'  => '',
                'icon'   => 'ion ion-ios-gear-outline',
                'iconbg' => 'bg-aqua',
                'text'   => 'TruFit Total Enrollments',
                'value'  => $records->total
            ];
        }

        return $results;
    }

    public function getTotalLeads()
    {
        $results = [];

        // @todo - fine tune this query to be more accurate
        $records = $this->leads->select(DB::raw('count(*) as total'))->first();

        if(!is_null($records))
        {
            $results = [
                'class'  => '',
                'icon'   => 'ion ion-ios-gear-outline',
                'iconbg' => 'bg-red',
                'text'   => 'TruFit Total Leads',
                'value'  => $records->total
            ];
        }

        return $results;
    }

    public function getReferralBreakdown()
    {
        $results = [];
        $bg_colors = ["#f56954","#00a65a","#f39c12",'#00c0ef','#3c8dbc', '#d2d6de', '#654321', '#800080'];

        // Query for the breakdown from the referrals table
        if($breakdown = $this->referrals->getReferralBreakDown())
        {
            $results['datasets'] = [];
            foreach ($breakdown as $idx => $type)
            {
                $results['labels'][] = $type['campaign'];
                $results['dataset'][] = $type['total'];
                $results['backgroundColor'][] = $bg_colors[$idx];
            }



            /**
             * Steps
             * 2. Query overall referrals and group by store
             *      - get total referrals between 60 and 31 days ago
             *      - get total referrals between 0 and 30 days ago
             * 3. Analyze Three stats -
             *      - whoever went up the highest between cycles
             *      - whoever went down lowest between cycles
             *
             */

            // Add the label at the end
            $results['name'] = 'TruFit Referral Breakdown';
        }

        return $results;
    }
}
