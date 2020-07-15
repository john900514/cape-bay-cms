<?php

namespace AnchorCMS\Http\Controllers\Admin;

use AnchorCMS\Roles;
use Backpack\CRUD\CrudPanel;
use Prologue\Alerts\Facades\Alert;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use AnchorCMS\Http\Requests\RolesRequest as StoreRequest;
use AnchorCMS\Http\Requests\RolesRequest as UpdateRequest;

/**
 * Class RolesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RolesCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('AnchorCMS\Roles');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-roles');
        $this->crud->setEntityNameStrings('roles', 'roles');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $name = [
            'name' => 'name', // the db column name (attribute name)
            'label' => "Role Name", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];
        $title = [
            'name' => 'title', // the db column name (attribute name)
            'label' => "Role Title", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];

        $abilities_box = [
            'name' => 'assignable_abilities',
            'label' => 'Assign Abilities',
            'type' => 'custom_html',
            'value' => '
                <label>Assign Abilities</label>
                <role-ability-assign></role-ability-assign>
            '
        ];

        $column_defs = [$name, $title];
        $edit_create_defs = [$name, $title, $abilities_box];
        $this->crud->addColumns($column_defs);
        $this->crud->addFields($edit_create_defs, 'both');
        // add asterisk for fields that are required in RolesRequest

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        //$redirect_location = parent::storeCrud();
        $new_role = Bouncer::role()->firstOrCreate([
            'name' => $request->all()['name'],
            'title' => $request->all()['title']
        ]);

        if($new_role)
        {
            Alert::success(trans('backpack::crud.insert_success'))->flash();
        }
        else
        {
            Alert::error(trans('backpack::crud.insert_fail'))->flash();
        }
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        // show a success message

        return redirect('/crud-roles');
    }

    public function update(UpdateRequest $request, Roles $role_model)
    {
        // your additional operations before save here
        //$redirect_location = parent::updateCrud($request);
        if(Bouncer::is(backpack_user())->a('god', 'admin'))
        {
            $requested_abilities = explode(',', $request->all()['abilities']);

            if(count($requested_abilities) == 1 && empty($requested_abilities[0]))
            {
                $requested_abilities[0] = $request->all()['abilities'];
            }

            foreach ($requested_abilities as $idx => $ab)
            {
                $requested_abilities[$ab] = $ab;
                unset($requested_abilities[$idx]);
            }

            $role = $request->all()['name'];
            $abilities = $role_model->getAssignedAbilities($role);

            if(count($abilities) > 0)
            {
                // retract any abilities not in $requested_abilities
                foreach ($abilities as $ability)
                {
                    if(!array_key_exists($ability['name'], $requested_abilities))
                    {
                        Bouncer::disallow($role)->to($ability['name']);
                    }
                }
            }

            foreach($requested_abilities as $req_ability)
            {
                Bouncer::allow($role)->to($req_ability);
            }

            Alert::success(trans('backpack::crud.insert_success'))->flash();
        }
        else
        {
            Alert::error('Access Denied. You do not have permission to update roles.')->flash();
        }

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return redirect('/crud-roles');
    }
}
