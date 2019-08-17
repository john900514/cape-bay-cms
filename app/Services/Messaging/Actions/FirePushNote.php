<?php

namespace App\Services\Messaging\Actions;

use App\MessagingTemplates;
use App\Services\ClientMgntService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Notifications\TruFit\GenericPushNote;
use App\Services\Messaging\PushNotificationsService;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;

class FirePushNote extends PushNotificationsService
{
    public $expoChannel;

    public function __construct(MessagingTemplates $templates, ClientMgntService $clients, ExpoChannel $channel)
    {
        parent::__construct($templates, $clients);
        $this->expoChannel = $channel;
    }

    public function fire(array $data)
    {
        $results = ['success' => false, 'reason' => 'Could not fire out messages to specified users. Please Try Again.'];

        // @todo - evaluate for the client_id
        $validated = Validator::make($data, [
            'users' =>  'required|array',
            'template_id' =>  'required|exists:messaging_templates,uuid',
        ]);

        if ($validated->fails())
        {
            foreach($validated->errors()->toArray() as $col => $msg)
            {
                $results['reason'] = $msg;
                break;
            }
        }
        else
        {
            // Pull the template record      (From base Messaging Svc)
            $template = $this->getTemplateByUUID($data['template_id']);

            // Pull the client data module   (From PushNotifications Messaging Svc)
            $data_module = $this->getClientDataModule($template['client_id']);

            // Get an instance of a push-notifiable model (From Client Data Module)
            $notifable_model = $data_module->getPushNotifiableModel();

            //The Laravel notifcation object
            $push_note = new GenericPushNote($template);

            // @todo - we need to see if the user(s) is/are subscribed before firing
            foreach ($data['users'] as $u_id => $notey)
            {
                $devic_user = $notifable_model->find($u_id);
                $this->subscribe($notey, $devic_user);
            }


            // Fire the message to all the users (From the PushNotifications Svc)
            $result = $this->execute($notifable_model, $data['users'], $push_note);

            if($result)
            {
                // Forward the Results Back to the Controller
                $results = ['success' => true, 'results' => $result];
            }
        }

        return $results;
    }

    public function subscribe($token, Model $model)
    {
        $interest = $this->expoChannel->interestName($model);

        try
        {
            $this->expoChannel->expo->subscribe($interest, $token);

            $results = [
                'status'    =>  'succeeded',
                'expo_token' => $token,
            ];

        } catch (\Exception $e) {
            $results = [
                'status'    => 'failed',
                'reason'     =>  $e->getMessage()
            ];
        }

        return $results;
    }

}
