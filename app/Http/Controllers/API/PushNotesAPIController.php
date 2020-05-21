<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\App;
use Ixudra\Curl\Facades\Curl;
use App\Models\TruFit\AppUsers;
use App\Models\TAC\Vapor\AppUser as TACAppUsers;
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
                switch($data['clientId'])
                {
                    case 7:
                        $push_note = new \App\Models\TAC\PushNotifications();
                        $push_note->text = $data['message'];
                        $push_note->date = date('Y-m-d m:i:s');//.'.000+00';
                        $push_note->number_recipients = count($data['users']);
                        $push_note->open_count = 0;
                        $push_note->did_send = true;
                        $push_note->save();
                        break;

                    case 2:
                    default:
                        $push_note = new \App\Models\TruFit\PushNotifications();
                        $push_note->text = $data['message'];
                        $push_note->date = date('Y-m-d m:i:s').'.000+00';
                        $push_note->number_recipients = count($data['users']);
                        $push_note->open_count = 0;
                        $push_note->did_send = true;
                        $push_note->save();
                }

                if(count($data['users']) <= 100)
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
                                    $url = is_null($data['url']) ? '' : $data['url'];
                                    $app_user->notify(new FireExpoPushNote($data['message'], $push_note->id, $url));
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
                                    Log::info('Unable to locate user with token -'.$user['uuid']);
                                }
                            }
                        }
                    }

                    foreach($batch as $page => $expo_users) {
                        $proj = ($data['clientId'] == 2) ? 'trufit' : 'the-athletic-club';
                        $payload = [
                            'to' => $expo_users,
                            'title' => 'Mobile Announcement',
                            'body' => $data['message'],
                            '_category' => '@capeandbay/' . $proj . ':announce',
                            '_displayInForeground' => true,
                            'data' => [
                                'pushnotes_id' => $push_note->id,
                                'add_user_id' => true
                            ]
                        ];

                        $url = is_null($data['url']) ? '' : $data['url'];
                        if ($url != '')
                        {
                            $payload['data']['url'] = $url;
                        }

                        $args = [
                            $payload
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
                                                'title' => 'Mobile Announcement',
                                                'body' => $data['message'],
                                                '_category'=> $project.':announce',
                                                '_displayInForeground'=> true
                                            ]
                                        ];

                                        $response = Curl::to('https://exp.host/--/api/v2/push/send')
                                            ->withData($args)
                                            ->asJson(true)
                                            ->post();

                                        if($project == '@waverlyandco/trufit')
                                        {
                                            $app_user = new AppUsers();
                                            $app_user->whereIn('expo_push_token', $tokens)->update(['expo_push_token'=> null]);

                                            /*
                                            foreach ($tokens as $token)
                                            {
                                                $app_user = new AppUsers();
                                                $app_user = $app_user->whereExpoPushToken($token)->first();

                                                if(!is_null($app_user))
                                                {
                                                    // Send before destroying

                                                    $app_user->expo_push_token = null;
                                                    $app_user->save();
                                                }
                                            }
                                            */

                                            Log::info('Could not send message because of Waverly.');
                                        }
                                    }
                                }
                            }
                        }
                        //Log:info('Response from Expo - ', $response);
                        Log::info("Sent Batch #{$page} to Expo");
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
                'reason'    =>  $e->getMessage()
            ];
        }

        return $results;
    }
}
