<?php

namespace App\Http\Controllers\Admin;

use Bouncer;
use App\Roles;
use App\Services\UserMgntService;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\UsersRequest as StoreRequest;
use App\Http\Requests\UsersRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class UsersCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UsersCrudController extends CrudController
{
    public function __construct(UserMgntService $user_svc)
    {
        parent::__construct();
        $this->user_svc = $user_svc;
    }

    public function setup()
    {
        $user = $this->user_svc->getUserRecordAndRole();
        $all_roles = Roles::all();
        $role_select = [];
        foreach ($all_roles as $role)
        {
            if($role->name == 'god' && Bouncer::is(backpack_user())->a('god'))
            {
                $role_select[$role->name] = $role->title;
            }
            else if($role->name == 'master' && Bouncer::is(backpack_user())->a('god','master'))
            {
                $role_select[$role->name] = $role->title;
            }
            else if( ($role->name != 'god') && ($role->name != 'master') )
            {
                $role_select[$role->name] = $role->title;
            }

        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\BackpackUser');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/batman/users/mgnt');
        $this->crud->setEntityNameStrings('User', 'users');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        //$this->crud->setFromDb();
        $user_actual_name = [
            'name' => 'name', // the db column name (attribute name)
            'label' => "Name", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];

        $user_email = [
            'name' => 'email', // the db column name (attribute name)
            'label' => "Email", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];

        $user_password = [
            'name' => 'password', // the db column name (attribute name)
            'label' => "Password", // the human-readable label for it
            'type' => 'password' // the kind of column to show
        ];

        $user_role = [
            'name' => 'userrole.role.title', // the db column name (attribute name)
            'label' => "Role", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];

        $roles = [ // select_from_array
            'name' => 'role',
            'label' => "Role",
            'type' => 'select_from_array',
            'options' => $role_select,
        ];

        $column_defs = [$user_actual_name, $user_email, $user_role];
        $field_defs = [$roles];
        $both_defs = [$user_actual_name, $user_email, $user_password, $roles];

        $this->crud->addColumns($column_defs);
        $this->crud->addFields($both_defs,'both');

        // add asterisk for fields that are required in UsersRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');


        $this->data['menu_options'] = $this->user_svc->getDashMenuOptions($user['roles']);
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        $data = $request->all();
        // use $this->data['entry'] or $this->crud->entry
        $user = $this->data['entry'];
        $user->password = bcrypt($data['password']);
        $user->save();

        Bouncer::assign($data['role'])->to($user);
;
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $cached_user = backpack_user()->find($request->get('id'));

        if(is_null($request->get('password')))
        {
            $request->merge(['password'=> $cached_user->password]);
        }
        else
        {
            $request->merge(['password'=> bcrypt($request->get('password'))]);
        }

        $data = $request->all();
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        $user = $this->data['entry'];

        if(!Bouncer::is($user)->a($data['role']))
        {
            $user_roles = $user->getRoles();

            if(count($user_roles) > 0)
            {
                foreach ($user_roles as $rol)
                {
                    Bouncer::retract($rol)->from($user);
                }
            }

            Bouncer::assign($data['role'])->to($user);

        }
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
