<?php

namespace App\Services;

use App\MessagingTemplates;

class BaseMessagingService
{
    public $templates;
    protected $msg_type;

    public function __construct(MessagingTemplates $templates)
    {
        $this->templates = $templates;
    }

    public function getTemplates($client_id = null)
    {
        $results = [];

        $records = $this->templates->getTemplates($this->msg_type, $client_id);

        if($records && count($records) > 0)
        {
            $results = $records->toArray();
        }

        return $results;
    }

    public function getTemplateByUUID($uuid)
    {
        $results = false;

        $record = $this->templates->where('uuid',$uuid)->first();

        if(!is_null($record))
        {
            $results = $record->toArray();
        }

        return $results;
    }

    public function setMsgType($to)
    {
        $this->msg_type = $to;
    }
}
