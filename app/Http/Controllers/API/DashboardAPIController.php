<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardAPIController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        //$this->middleware('auth');
        $this->request = $request;
    }

    public function get_info_box_grid_data(string $client)
    {
        $results = ['success' => false];

        $user = backpack_user();

        if(!is_null($user))
        {
            // @todo - get the real data
            $boxes = [
                [
                    'class'  => '',
                    'icon'   => 'ion ion-ios-gear-outline',
                    'iconbg' => 'bg-aqua',
                    'text'   => 'TruFit Total Sales',
                    'value'  => '3559'
                ],
                [
                    'class'  => '',
                    'icon'   => 'ion ion-ios-gear-outline',
                    'iconbg' => 'bg-red',
                    'text'   => 'TruFit Total Leads',
                    'value'  => '14528'
                ],
                [
                    'class'  => '',
                    'icon'   => 'ion ion-ios-gear-outline',
                    'iconbg' => 'bg-green',
                    'text'   => 'THE Athletic Club Sales',
                    'value'  => '17'
                ],
                [
                    'class'  => '',
                    'icon'   => 'ion ion-ios-gear-outline',
                    'iconbg' => 'bg-orange',
                    'text'   => 'THE Athletic Club Leads',
                    'value'  => '931'
                ]
            ];
            $results = ['success' => true, 'data' => $boxes];
        }

        return response()->json($results);
    }
}
