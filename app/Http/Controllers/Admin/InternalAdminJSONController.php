<?php

namespace AnchorCMS\Http\Controllers\Admin;

use AnchorCMS\Abilities;
use AnchorCMS\Roles;
use Illuminate\Http\Request;
use AnchorCMS\Http\Controllers\Controller;
use Silber\Bouncer\BouncerFacade as Bouncer;

class InternalAdminJSONController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function abilities(Abilities $abilities)
    {
        $results = ['success' => false, 'reason' => 'You do not have permission to access this resource'];

        if(Bouncer::is(backpack_user())->a('god','admin'))
        {
            $res = [];
            $records = $abilities->all();

            if(count($records) > 0)
            {
                foreach ($records as $ability)
                {
                    $res[$ability->name] = $ability->title;
                }
            }

            $results = ['success' => true, 'abilities' => $res];
        }

        return response($results, 200);
    }

    public function role_abilities($role, Roles $roles)
    {
        $results = ['success' => false, 'reason' => 'You do not have permission to access this resource'];

        if(Bouncer::is(backpack_user())->a('god','admin', $role))
        {
            $assigned = $roles->getAssignedAbilities($role);

            $results = ['success' => true, 'assigned' => []];
            if(count($assigned) > 0)
            {
                $results['assigned'] = $assigned;
            }
        }

        return response($results, 200);
    }
}
