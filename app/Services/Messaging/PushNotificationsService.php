<?php

namespace App\Services\Messaging;

use App\MessagingTemplates;
use App\Services\ClientMgntService;
use App\Services\BaseMessagingService;


class PushNotificationsService extends BaseMessagingService
{
    protected $client_svc;

    public function __construct(MessagingTemplates $templates, ClientMgntService $clients)
    {
        parent::__construct($templates);
        $this->setMsgType('push');
        $this->client_svc = $clients;
    }

    public function getClientDataModule($client_id)
    {
        $results = false;

        $client = $this->client_svc->getClient($client_id);
        $module = $this->client_svc->getClientDataModule($client->uuid, $client);

        if($module)
        {
            $results = $module;
        }

        return $results;
    }

    public function execute($notifiable_model, $notifiables, $note_obj)
    {
        $results = false;

        if (count($notifiables) > 0)
        {
            foreach ($notifiables as $notifiable_id => $push_token)
            {
                $notifiable = $notifiable_model->find($notifiable_id);

                if(!is_null($notifiable))
                {
                    try {
                        $status = $notifiable->notify($note_obj);
                        $results = ['success' => true];
                    }
                    catch (\Exception $e)
                    {
                        $results['reason'] = $e->getMessage();
                    }
                }
            }
        }


        return $results;
    }
}
