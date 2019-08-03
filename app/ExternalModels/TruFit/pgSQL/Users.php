<?php

namespace App\ExternalModels\TruFit\pgSQL;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use NotificationChannels\ExpoPushNotifications\Models\Interest;

class Users extends Model
{
    use LogsActivity, Notifiable;

    protected $table = 'users';
    protected $connection = 'tfpgsql';
    public $timestamps = false;

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
