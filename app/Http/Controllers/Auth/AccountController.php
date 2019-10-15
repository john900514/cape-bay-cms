<?php

namespace App\Http\Controllers\Auth;

use Alert;
use Bouncer;
use Backpack\Base\app\Http\Controllers\Controller;
use Backpack\Base\app\Http\Requests\AccountInfoRequest;
use Backpack\Base\app\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    protected $data = [];

    public function __construct()
    {
        $this->middleware(backpack_middleware());
    }

    /**
     * Show the user a form to change his personal information.
     */
    public function getAccountInfoForm()
    {
        $this->data['title'] = trans('backpack::base.my_account');
        $this->data['user'] = backpack_user();

        $this->data['client_id'] = $this->getClientID();

        return view('backpack::auth.account.update_info', $this->data);
    }

    public function getChangePasswordForm()
    {
        $this->data['title'] = trans('backpack::base.my_account');
        $this->data['user'] = backpack_user();
        $this->data['client_id'] = $this->getClientID();

        return view('backpack::auth.account.change_password', $this->data);
    }

    private function getClientID()
    {
        $results = false;
        if(Bouncer::is(backpack_user())->a('client'))
        {
            $abilities = backpack_user()->getAbilities()
                ->where('name', '=', 'access-client');

            if(count($abilities) > 0)
            {
                $results = $abilities[0]->entity_id;
            }
        }
        else
        {
            $results = 1;
        }

        return $results;
    }
}
