<?php

namespace App\Actions\PushNotes;

use Ixudra\Curl\Facades\Curl;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;

class FireMassPushNotes
{
    protected $action, $expo_channel;

    public function __construct(ExpoChannel $channel, GetPushNotesUsers $action)
    {
        $this->action = $action;
        $this->expo_channel = $channel;
    }

    public function execute($client_id, $type, $title, $msg, $url, $command = null)
    {
        $this->info('Processing and firing...', $command);

        // Validate the client id or fail msg, done
        $valid = true;
        switch($client_id)
        {
            case 2:
                $feature_id = 1;
                break;

            case 7:
                $feature_id = 3;
                break;

            default:
                $this->info('Invalid Client - '.$client_id, $command);
                $valid = false;
        }

        if($valid)
        {
            // Get all the users.
            $users = $this->action->execute($client_id, $feature_id);

            if(count($users['users']) > 0)
            {
                if(env('APP_ENV') != 'production')
                {
                    //obtain Angels's account and mod the results array
                    $temp_user = collect($users['users'])
                        ->where('email', '=', 'angel+l@capeandbay.com')
                        ->first();

                    $users['users'] = [$temp_user];
                }

                // batch send 100 users at a time to an API call
                $member_count = 0;
                $batch_count = 0;
                $batch = [];

                $this->info('Setting Batches', $command);

                foreach($users['users'] as $idx => $user)
                {
                    $loggy = ' ---->Hoping to send push note to user '.$user['email'];
                    $command->progress_bar($idx, count($users['users']), $loggy);

                    $member_count++;

                    if($member_count == 99)
                    {
                        $member_count = 0;
                        $batch_count++;
                    }

                    $batch[$batch_count][] = $user['expo_push_token'];
                }

                foreach($batch as $page => $expo_users)
                {
                    $command->progress_bar($page, count($batch));
                    $proj = ($client_id == 2) ? 'trufit' : 'the-athletic-club';
                    $args = [
                        [
                            'to' => $expo_users,
                            'title' => $title,
                            'body' => $msg,
                            '_category'=> '@capeandbay/'.$proj.':announce',
                            '_displayInForeground'=> true,
                            'data' => [
                                'url' => $url,
                            ],
                            'sound' => 'default'
                        ]
                    ];

                    $response = Curl::to('https://exp.host/--/api/v2/push/send')
                        ->withData($args)
                        ->asJson(true)
                        ->post();

                    // This is fucking code to remove edgar's fucking hold on these fucking users
                    if(array_key_exists('errors', $response))
                    {
                        foreach ($response['errors'] as $idx => $error)
                        {
                            if(array_key_exists('details', $error))
                            {
                                foreach ($error['details'] as $project => $tokens)
                                {
                                    // Attempt to send via project
                                    $args = [
                                        [
                                            'to' => $tokens,
                                            'title' => $title,
                                            'body' => $msg,
                                            '_category'=> $project.':announce',
                                            '_displayInForeground'=> true,
                                            'data' => [
                                                'url' => $url,
                                            ]
                                        ]
                                    ];

                                    $response = Curl::to('https://exp.host/--/api/v2/push/send')
                                        ->withData($args)
                                        ->asJson(true)
                                        ->post();

                                    if($project == '@waverlyandco/trufit')
                                    {
                                        $this->info('Could not send message because of Waverly.', $command);
                                    }
                                }
                            }
                        }
                    }

                    $this->info("Sent Batch #{$page} to Expo", $command);
                }
            }
            else
            {
                $this->info('No users to send to.', $command);
            }

        }
    }

    private function info($msg, $command = null)
    {
        if(!is_null($command))
        {
            $command->info($msg);
        }
    }

}
