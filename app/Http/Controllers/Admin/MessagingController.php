<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessagingController extends Controller
{
    protected $request;

    public  function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function push_notes_index()
    {
        return view('anchor.messaging.push-notes.index');
    }
}
