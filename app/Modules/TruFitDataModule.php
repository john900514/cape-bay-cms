<?php

namespace App\Modules;

use App\Repositories\BaseRepository;
use App\ExternalModels\TruFit\mySQL\Leads;
use App\ExternalModels\TruFit\mySQL\Stores;
use App\ExternalModels\TruFit\pgSQL\Locations;
use App\ExternalModels\TruFit\mySQL\Referrals;
use App\ExternalModels\TruFit\mySQL\Conversions;

class TruFitDataModule {

    protected $client_id = '43d798ee-3247-4749-90a4-346b41d3e745';
    protected $truFitRepo;

    public function __construct()
    {
        $this->truFitRepo = [
            'web' => [
                'stores' => new BaseRepository(new Stores()),
                'leads' => new BaseRepository(new Leads()),
                'conversions' => new BaseRepository((new Conversions())),
                'referrals' => new BaseRepository((new Referrals()))
            ],
            'mobile' => [
                'locations' => new BaseRepository(new Locations())
            ]
        ];
    }

    public function getModuleRepos()
    {
        // @todo - be sure to hardcode this
        return [
            [
                'name' => 'Clubs',
                'url' => "records/{$this->client_id}/clubs"
            ]
        ];
    }

    public function getModuleRepo($repoName)
    {
        switch($repoName)
        {
            case 'clubs':
                $stores_model = Stores::with('location_in_mobile_app')->get();

                if(count($stores_model) > 0)
                {
                    $results = ['' => 'Select a Club'];
                    foreach($stores_model->toArray() as $club)
                    {
                        $results[$club['ClubId']] = $club['ClubName'];
                    }

                }
                break;

            default:
                $results = false;
        }

        return $results;
    }

    public function getModuleReports()
    {
        // @todo - be sure to make this db driven
        return [
            [
                'uuid' => 'dfc1108f-f556-4255-afff-6cce4b99c57e',
                'name' => 'Payment Form Leads',
                'url' => "reports/{$this->client_id}/payment-leads"
            ],
            [
                'uuid' => 'd5405adc-952a-48a0-a0d8-47cfcdd88f8d',
                'name' => 'Payment Form Conversions',
                'url' => "reports/{$this->client_id}/payment-conversions"
            ],
            [
                'uuid' => '1bd6e4b3-2940-405a-aac9-5432d5199feb',
                'name' => 'Buddy Referral Leads',
                'url' => "reports/{$this->client_id}/referral-leads"
            ],
            [
                'uuid' => '1f354a17-040e-4603-b4eb-21694803e539',
                'name' => 'Combo6 Referral Leads',
                'url' => "reports/{$this->client_id}/combo6-leads"
            ],
        ];
    }

    public function getModuleReport($uuid)
    {
        // @todo - ugh, make this DB driven
        switch($uuid)
        {
            // Payment Form Leads
            case 'dfc1108f-f556-4255-afff-6cce4b99c57e':
            case 'payment-leads':
                $results = [
                    'name' => 'Payment Form Leads',
                    'results' => [],
                    'fields' => [
                        [
                            'key'=> '#',
                            'sortable'=> true
                        ],
                        [
                            'key' => 'First Name',
                            'sortable' => true
                        ],
                        [
                            'key' => 'Last Name',
                            'sortable' => true
                        ],
                        [
                            'key' => 'Club',
                            'sortable' => true
                        ],
                        [
                            'key' => 'Plan',
                            'sortable' => true
                        ],
                        [
                            'key' => 'Contract Price',
                            'sortable' => true
                        ],
                        [
                            'key' => 'PromoCode',
                            'sortable' => true
                        ],
                        [
                            'key' => 'Captured',
                            'sortable' => true
                        ],
                        [

                            'key' => 'Abandoned',
                            'sortable' => true
                        ]
                    ]
                ];

                $report = $this->truFitRepo['web']['leads']->all();

                if(count($report) > 0)
                {
                    foreach($report->toArray() as $idx => $r)
                    {
                        $plan_info = json_decode($r['plan_info'], true);
                        $scout = [
                            '#' => $idx + 1,
                            'First Name' => $r['first_name'],
                            'Last Name' => $r['last_name'],
                            'Club' => $r['paramount_club_id'],
                            'Plan' => array_key_exists('Description', $plan_info) ? $plan_info['Description'] : 'N/A',
                            'Contract Price' => array_key_exists('ContractPrice', $plan_info) ? $plan_info['ContractPrice'] : '0.00',
                            'PromoCode' => array_key_exists('PromoCode', $plan_info) ? $plan_info['PromoCode'] : 'unknown',
                            'Captured' => $r['created_at'],
                            'Abandoned' => $r['abandoned'] === 1
                        ];

                        $results['results'][$idx] = $scout;
                    }
                }

                break;

            // Payment Form Conversions
            case 'd5405adc-952a-48a0-a0d8-47cfcdd88f8d':
            case 'payment-conversions':
                $results = [
                    'name' => 'Payment Form Conversions',
                    'results' => [],
                    'fields' => [
                        [
                            'key'=> 'Contract #',
                            'sortable'=> true
                        ],
                        [
                            'key' => 'First Name',
                            'sortable' => true
                        ],
                        [
                            'key' => 'Last Name',
                            'sortable' => true
                        ],
                        [
                            'key' => 'Club',
                            'sortable' => true
                        ],
                        [
                            'key' => 'Plan',
                            'sortable' => true
                        ],
                        [
                            'key' => 'Down Payment',
                            'sortable' => true
                        ],
                        [
                            'key' => 'PromoCode',
                            'sortable' => true
                        ],
                        [
                            'key' => 'Enrolled On',
                            'sortable' => true
                        ],
                    ]
                ];

            $report = $this->truFitRepo['web']['conversions']->getModel()
                ->with('lead')
                ->get();

            if(count($report) > 0)
            {
                foreach($report->toArray() as $idx => $r)
                {
                    $plan_info = json_decode($r['lead']['plan_info'], true);
                    $scout = [
                        'Contract #' => $r['ContractNumber'],
                        'First Name' => $r['lead']['first_name'],
                        'Last Name' => $r['lead']['last_name'],
                        'Club' => $r['lead']['paramount_club_id'],
                        'Plan' => array_key_exists('Description', $plan_info) ? $plan_info['Description'] : 'N/A',
                        'Down Payment' => array_key_exists('DownPayment', $plan_info) ? '$'.$plan_info['DownPayment'] : '$0.00',
                        'PromoCode' => array_key_exists('PromoCode', $plan_info) ? $plan_info['PromoCode'] : 'unknown',
                        'Enrolled On' => $r['created_at']
                    ];

                    $results['results'][$idx] = $scout;
                }
            }
                break;

            //Buddy Referral Leads
            case '1bd6e4b3-2940-405a-aac9-5432d5199feb':
            case 'referral-leads':
                $results = [
                    'name' => 'Buddy Referral Leads',
                    'results' => [],
                    'fields' => [
                        [
                            'key'=> '#',
                            'sortable'=> true
                        ],
                        [
                            'key'=> 'ReferralName',
                            'sortable'=> true
                        ],
                        [
                            'key'=> 'BuddyFirst',
                            'sortable'=> true
                        ],
                        [
                            'key'=> 'BuddyLast',
                            'sortable'=> true
                        ],
                        [
                            'key'=> 'Club',
                            'sortable'=> true
                        ],
                        [
                            'key'=> 'Email',
                            'sortable'=> true
                        ],
                        [
                            'key'=> 'Mobile',
                            'sortable'=> true
                        ]
                    ]
                ];

                $report = $this->truFitRepo['web']['referrals']->getModel()
                    ->where('campaign', '=', 'buddy')
                    ->get();

                if(count($report) > 0)
                {
                    foreach($report->toArray() as $idx => $r)
                    {
                        $scout = [
                            '#' => $idx + 1,
                            'ReferralName' => $r['referral_name'],
                            'BuddyFirst' => $r['first_name'],
                            'BuddyLast' => $r['last_name'],
                            'Club' => $r['club'],
                            'Email' => $r['email'],
                            'Mobile' => $r['mobile'],
                        ];

                        $results['results'][$idx] = $scout;
                    }
                }

                break;

            case '1f354a17-040e-4603-b4eb-21694803e539':
            case 'combo6-leads':
                $results = [
                    'name' => 'Payment Form Leads',
                    'results' => [],
                    'fields' => [
                        [
                            'key'=> '#',
                            'sortable'=> true
                        ],
                        [
                            'key' => 'FirstName',
                            'sortable' => true
                        ],
                        [
                            'key' => 'LastName',
                            'sortable' => true
                        ],
                        [
                            'key' => 'Club',
                            'sortable' => true
                        ],
                        [
                            'key' => 'Email',
                            'sortable' => true
                        ],
                        [
                            'key' => 'Mobile',
                            'sortable' => true
                        ],
                    ]
                ];

            $report = $this->truFitRepo['web']['referrals']->getModel()
                ->where('campaign', '=', 'combo6')
                ->get();

            if(count($report) > 0)
            {
                foreach($report->toArray() as $idx => $r)
                {
                    $scout = [
                        '#' => $idx + 1,
                        'FirstName' => $r['first_name'],
                        'LastName' => $r['last_name'],
                        'Club' => $r['club'],
                        'Email' => $r['email'],
                        'Mobile' => $r['mobile'],
                    ];

                    $results['results'][$idx] = $scout;
                }
            }
            break;

            default:
                $results = false;
        }

        return $results;
    }
}
