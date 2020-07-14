<?php

namespace AnchorCMS\Http\Controllers\Admin;

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

        $column_defs = [$name, $title];
        $this->crud->addColumns($column_defs);
        $this->crud->addFields($column_defs, 'both');
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
            \Alert::success(trans('backpack::crud.insert_success'))->flash();
        }
        else
        {
            \Alert::error(trans('backpack::crud.insert_fail'))->flash();
        }
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        // show a success message

        return redirect('/crud-roles');
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
