<?php

namespace App\Http\Controllers\API;

use Ixudra\Curl\Facades\Curl;
use App\Models\TruFit\AppUsers;
use App\Models\TAC\AppUsers as TACAppUsers;
use App\Actions\PushNotes\GetPushNotesUsers;
use App\Notifications\FireExpoPushNote;
use App\Services\PNMobileAppService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;

class PushNotesAPIController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function fire(ExpoChannel $channel)
    {
        $results = ['success' => false];

        $data = $this->request->all();
        if(array_key_exists('clientId', $data))
        {
            if($data['clientId'] == 2 || $data['clientId'] == 7)
            {
                if(count($data['users']) <= 30)
                {
                    foreach($data['users'] as $idx => $user)
                    {
                        if(array_key_exists('push_type', $user))
                        {
                            if($user['push_type'] == 'mobile')
                            {
                                $app_user = ($data['clientId'] == 2)
                                    ? AppUsers::where('expo_push_token','=', $user['push_token'])->first()
                                    : TACAppUsers::where('expo_push_token','=', $user['push_token'])->first()
                                ;

                                if(!is_null($app_user))
                                {
                                    Log::info('Hoping to send push note to user '.$app_user->id);
                                    $this->subscribe($user['push_token'], $app_user, $channel);
                                    $app_user->notify(new FireExpoPushNote($data['message'], $data['url']));

                                    // @todo - log the push note in the client's push_notes pgSQL table

                                }
                                else
                                {
                                    Log::info('Unable to locate user with token -'.$app_user->id);
                                }
                            }
                            elseif($user['push_type'] == 'wallet')
                            {

                            }
                        }
                    }
                }
                else
                {
                    // batch send 100 users at a time to an API call
                    $member_count = 0;
                    $batch_count = 0;
                    $batch = [];

                    foreach($data['users'] as $idx => $user)
                    {
                        if(array_key_exists('push_type', $user))
                        {
                            if ($user['push_type'] == 'mobile')
                            {
                               // $app_user = AppUsers::where('expo_push_token','=', $user['push_token'])->first();
                                if(true)//!is_null($app_user))
                                {
                                    Log::info('Hoping to send push note to user '.$user['email']);
                                    //$this->subscribe($user['push_token'], $app_user, $channel);
                                    $member_count++;

                                    if($member_count == 99)
                                    {
                                        $member_count = 0;
                                        $batch_count++;
                                    }

                                    $batch[$batch_count][] = $user['push_token'];
                                }
                                else
                                {
                                    Log::info('Unable to locate user with token -'.$app_user->id);
                                }
                            }
                        }
                    }

                    foreach($batch as $page => $expo_users)
                    {
                        $hello = 'hey!';
                        $args = [

                            [
                                'to' => $expo_users,
                                'title' => 'TruFit Announcements',
                                'body' => $data['message'],
                                '_category'=> '@capeandbay/trufit:announce',
                                '_displayInForeground'=> true
                            ],

                            [
                                'to' => 'ExponentPushToken[4n9_WhLIx7z8HXj7Q8Ctnq]',
                                'title' => 'Push Notification Status',
                                'body' => 'Messages were sent!',
                                '_category'=> '@capeandbay/trufit:announce',
                                '_displayInForeground'=> true
                            ]
                        ];

                        $response = Curl::to('https://exp.host/--/api/v2/push/send')
                            ->withData($args)
                            ->asJson(true)
                            ->post();

                        //Log:info('Response from Expo - ', $response);
                        Log:info("Sent Batch #{$page} to Expo");
                    }
                }

                $results = ['success' => true];
            }
            //@todo - support other clients here
            //@todo - make this dynamic
        }

        return response()->json($results);
    }

    public function get_push_notes_users(int $client_id, int $feature_id, GetPushNotesUsers $action)
    {
        $results = $action->execute($client_id, $feature_id);

        return response()->json($results);
    }

    private function subscribe($token, Model $model, ExpoChannel $channel)
    {
        $interest = $channel->interestName($model);

        try
        {
            $channel->expo->subscribe($interest, $token);

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
