<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserMgntService;


class HomeController extends Controller
{
    protected $request;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        //$this->middleware('auth');

        $this->request = $request;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(UserMgntService $user_svc)
    {
        $args = [];
        /**
         * Steps
         * 1. Get the logged in user
         * 2. Get all records from menu options either NULL or the user's role
         *      arranged in 'order' order asc
         * 3. Pass Along to the view
         */
        $user = $user_svc->getUserRecordAndRole();
        $args['menu_options'] = $user_svc->getDashMenuOptions($user['roles']);


        return view('vendor.backpack.base.dashboard', $args);
    }
}
