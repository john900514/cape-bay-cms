<?php

namespace App\Services\Clients;

//use App\Models\TAC\Leads;
//use App\Models\TAC\AppUsers;
use App\Models\TAC\Vapor\Leads;
use App\Models\TAC\Vapor\AppUser;
use App\Models\TAC\Transactions;
use Illuminate\Support\Facades\DB;

class TheAtheticClubClientService
{
    protected $leads, $transactions;

    public function __construct(AppUser $app_users,Transactions $transactions, Leads $leads)
    {
        $this->leads = $leads;
        $this->app_users = $app_users;
        $this->transactions = $transactions;
    }

    public function getTotalSales()
    {
        $results = [];

        $records = $this->transactions->select(DB::raw('count(*) as total'))
            ->where('type', '=', 'membership')
            ->first();

        if(!is_null($records))
        {
            $results = [
                'class'  => '',
                'icon'   => 'ion ion-ios-gear-outline',
                'iconbg' => 'bg-green',
                'text'   => 'TAC Enrollments',
                'value'  => $records->total,
                'url'    => '/cms/7/enrollments'
            ];
        }

        return $results;
    }

    public function getTotalLeads()
    {
        $results = [];

        $records = $this->leads->select(DB::raw('count(*) as total'))->first();

        if(!is_null($records))
        {
            $results = [
                'class'  => '',
                'icon'   => 'ion ion-ios-gear-outline',
                'iconbg' => 'bg-orange',
                'text'   => 'TAC Leads',
                'value'  => $records->total,
                'url'    => '/cms/7/leads/'
            ];
        }

        return $results;
    }

    public function getUsers()
    {
        return $this->getMobileUsers();
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
}
