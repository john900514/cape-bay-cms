<?php

namespace App\Models\TAC\Vapor;

use App\Traits\UuidModel;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AppUser extends Authenticatable implements JWTSubject
{
    use LogsActivity, Notifiable, SoftDeletes, UuidModel;

    protected $connection = 'tac-mobile-app-mysql';
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id','password', 'remember_token','deleted_at','email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function addCredits($user_id, $points = 0)
    {
        $results = false;

        $model = $this->where('member_id', '=', $user_id);

        if($model)
        {
            $model->credits += $points;

            if($model->save())
            {
                $results = $model;
            }
        }

        return $results;
    }

    public function getMobileUsers()
    {
        $results = false;

        $records = $this->whereNotNull('password')
            ->whereNotNull('expo_push_token')
            ->get();

        if(count($records) > 0)
        {
            // @todo - place this snippet somewhere else
            /*
            $interest = new Interest();
            $class_name = $this->getMorphClass();
            $class_dots = str_replace("\\", '.', $class_name);
            // before passing them on, make sure the interests are set
            foreach ($records as $muser)
            {
                // cut or update interest record
                $model = $interest->where('entity_id', '=', $muser->id)
                    ->where('entity_type', '=', $class_name)
                    ->first();

                if(!is_null($model))
                {
                    //$model->value = $muser->expo_push_token;
                    //$model->save();
                }
                else
                {
                    $model = new Interest();
                    $model->key = $class_dots.".{$muser->id}";
                    $model->value = $muser->expo_push_token;
                    $model->entity_id = $muser->id;
                    $model->entity_type = $class_name;
                    $model->save();
                }
            }
            */
            $results = $records;
        }

        return $results;
    }
}
