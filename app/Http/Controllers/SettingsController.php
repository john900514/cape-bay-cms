<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $args = [
            'page_name' => ''
        ];

        return view('settings.generic-settings', $args);
    }

    public function admin_menu()
    {
        $args = [
            'page_name' => 'Admin'
        ];

        return view('settings.generic-settings', $args);
    }
}
