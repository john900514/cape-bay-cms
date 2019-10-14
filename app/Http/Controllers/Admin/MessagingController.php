<?php

namespace App\Http\Controllers\Admin;

use Bouncer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessagingController extends Controller
{
    protected $request;

    public  function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function push_notes_index(string $client_id = '1')
    {
        $props = [
            'client_id' => 1
        ];
        // Right now clients don't have access to this feature
        if(Bouncer::is(backpack_user())->a('client'))
        {
            return redirect('/dashboard');
        }
        else
        {
            if($client_id != '1')
            {
                $props['client_id'] = $client_id;
            }
        }

        return view('anchor.messaging.push-notes.index', $props);
    }
}
