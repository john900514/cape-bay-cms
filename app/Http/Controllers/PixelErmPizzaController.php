<?php

namespace App\Http\Controllers;

use App\Services\ClientMgntService;
use Illuminate\Http\Request;
use App\Services\Pixels\ProcessPixelData;

class PixelErmPizzaController extends Controller
{
    protected $request, $clients;

    public function __construct(Request $request, ClientMgntService $clients)
    {
        $this->request = $request;
        $this->clients = $clients;
    }

    private function verifyClientId($client_id)
    {
        $results = false;

        $client = $this->clients->getClient($client_id);

        if($client)
        {
            $results = $client;
        }

        return $results;
    }

    public function get_pixel($client_id, ProcessPixelData $pixel_svc)
    {
        //Verify the client id or fail
        if($client = $this->verifyClientId($client_id))
        {
            // Extract the query string from request
            $data = $this->request->all();

            // Send it to the doSomething action (if fail, then fail)
            $results = $pixel_svc->execute($data, $client);

            if($results)
            {
                // Send back the pixel lol
                $filename = 'pixel.png';
                $tempImage = tempnam(sys_get_temp_dir(), $filename);
                copy('https://amchorcms-assets.s3.amazonaws.com/pixel.png', $tempImage);

                return response()->header('Content-Type', 'text/javascript')
                    ->download($tempImage, $filename);
            }
            else
            {
                return response('Unreadable pixel data', 420);
            }
        }
        else
        {
            return response('Invalid client', 421);
        }
    }

    public function get_pixel_js($client_id)
    {
        $filename = 'capeandbaypixel.js';
        $tempImage = tempnam(sys_get_temp_dir(), $filename);
        //copy('https://amchorcms-assets.s3.amazonaws.com/capeandbaypixel.js', $tempImage);
        copy(public_path().'/js/capeandbaypixel.js', $tempImage);

        return response('',200)
            ->header('Content-Type', 'text/javascript')
            ->download($tempImage, $filename);
    }
}
