<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConnectWifiController
{
    protected  $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $args = [
            'url' => env('APP_URL').'/Signed-CnBWifi.mobileconfig',
        ];
        return view('anchor.wifi.connect', $args);
    }
}
