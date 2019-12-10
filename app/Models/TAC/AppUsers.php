<?php

namespace App\Models\TAC;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AppUsers extends Authenticatable implements JWTSubject
{
    use LogsActivity, Notifiable;

    protected $table = 'users';
    public $timestamps = false;
    protected $connection = 'tac-mobile-app-pgsql';

    protected static $logName = 'app-users';
    protected static $logAttributes = [
        'email', 'member_id',
        'first_name', 'last_name',
        'barcode','home_club_id'
    ];

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
        'remember_token',
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

    /**
     * Return a key value , containing any custom claims to be added to the JWT.
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "This record has been {$eventName}";
    }

    public function insertNew($data)
    {
        $results = false;

        $model = new $this;

        foreach ($data as $val => $datum) {
            $model->$val = $datum;
        }

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }

    public function addCredits($user_id, $points = 0)
    {
        $results = false;

        $model = $this->find($user_id);

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

    public function getAllMemberIDs()
    {
        $results = false;

        $records = $this->select('member_id')->get();

        if(count($records) > 0)
        {
            $temp = [];
            foreach($records as $member_no)
            {
                $temp[] = $member_no->member_id;
            }

            $results = $temp;
        }

        return $results;
    }

    public function club_member_via_member_id()
    {
        return $this->hasOne('App\Models\ClubMembers', 'contract_no', 'member_id');
    }

    public function club_member_via_email()
    {
        return $this->hasOne('App\Models\ClubMembers', 'email', 'email');
    }

    public function club_member_via_club_member_uuid()
    {
        return $this->hasOne('App\Models\ClubMembers', 'uuid', 'member_user_id');
    }

    public function tryToGetClubMember($model, $style = 'club_member_via_member_id')
    {
        $results = false;

        switch($style)
        {
            case 'club_member_via_member_id':
                $record = $model->$style()->first();

                if(is_null($record))
                {
                    return $this->tryToGetClubMember($model, 'club_member_via_email');
                }
                break;

            case 'club_member_via_email':
                $record = $model->$style()->first();

                if(is_null($record))
                {
                    return $this->tryToGetClubMember($model, 'club_member_via_club_member_uuid');
                }
                break;

            case 'club_member_via_club_member_uuid':
                $record = $model->$style()->first();
                break;

            default:
                $record = false;
        }

        if($record)
        {
            $results = $record;
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
