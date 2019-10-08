<?php

namespace App\Http\Controllers\API;

use App\Models\TruFit\AppUsers;
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
            if($data['clientId'] == 2)
            {
                foreach($data['users'] as $idx => $user)
                {
                    if(array_key_exists('push_type', $user))
                    {
                        if($user['push_type'] == 'mobile')
                        {
                            $app_user = AppUsers::where('expo_push_token','=', $user['push_token'])->first();

                            if(!is_null($app_user))
                            {
                                Log::info('Hoping to send push note to user ', $app_user->toArray());
                                $this->subscribe($user['push_token'], $app_user, $channel);
                                $app_user->notify(new FireExpoPushNote($data['message']));
                            }
                            else
                            {
                                Log::info('Unable to locate user with token -'.$user['push_token']);
                            }
                        }
                        elseif($user['push_type'] == 'wallet')
                        {

                        }
                    }
                }

                $results = ['success' => true];
            }
            //@todo - support other clients here
            // @todo - make this dynamic
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
