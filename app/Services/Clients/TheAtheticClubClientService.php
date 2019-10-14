<?php

namespace App\Services\Clients;

use App\Models\TAC\Leads;
use App\Models\TAC\Transactions;
use Illuminate\Support\Facades\DB;

class TheAtheticClubClientService
{
    protected $leads, $transactions;

    public function __construct(Transactions $transactions, Leads $leads)
    {
        $this->leads = $leads;
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
}
