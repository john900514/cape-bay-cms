<?php

namespace App;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class MessagingTemplates extends Model
{
    use LogsActivity, UuidModel, SoftDeletes;

    protected $table = 'messaging_templates';

    public function getTemplates($type, $client_id = null)
    {
        $results = false;

        $records = $this->where('messaging_type', '=', $type);

        if(!is_null($client_id))
        {
            $records = $records->where('client_id', '=', $client_id);
        }

        $records = $records->get();

        if(count($records) > 0)
        {
            $results = $records;
        }

        return $results;
    }

    public function renderMessage($message)
    {
        $results = $message;

        // @todo = insert biz rules here

        return $results;
    }
}
