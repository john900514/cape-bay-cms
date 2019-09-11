<?php

namespace App;

use Backpack\Base\app\Models\Traits\InheritsRelationsFromParentModel;
use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;
use Backpack\CRUD\CrudTrait;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class BackpackUser extends User
{
    use InheritsRelationsFromParentModel;
    use CrudTrait, HasRolesAndAbilities;

    protected $table = 'users';

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function userrole()
    {
        return $this->hasOne('App\AssignedRoles', 'entity_id', 'id')
            ->where('entity_type', '=', 'App\BackpackUser')
            ->with('role');
        /*
        return $this->hasOneThrough(
            'App\Roles',
            'App\AssignedRoles',
            'role_id',
            'id',
            'id',
            'entity_id');
        */
    }
}
