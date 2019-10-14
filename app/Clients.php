<?php

namespace App;

use App\Traits\UuidModel;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clients extends Model
{
    use HasRolesAndAbilities, SoftDeletes, UuidModel, LogsActivity, CrudTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    protected $guarded = ['id', 'uuid'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function data_module()
    {
        return $this->hasOne('App\ClientDataModule', 'client_id', 'id');
    }

    public function widgets()
    {
        return $this->hasMany('App\WidgetDefaults', 'client_id', 'id');
    }
}
