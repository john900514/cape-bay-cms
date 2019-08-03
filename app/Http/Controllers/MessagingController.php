<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserMgntService;
use App\Services\ClientMgntService;
use App\ExternalModels\TruFit\pgSQL\Users;
use App\Notifications\TruFit\WereGladYoureHere;
use App\Services\Messaging\Actions\FirePushNote;
use App\Services\Messaging\PushNotificationsService;

class MessagingController extends Controller
{
    protected $request, $user_svc, $client_svc;

    public function __construct(Request $request, UserMgntService $user_svc, ClientMgntService $client_svc)
    {
        $this->request = $request;
        $this->user_svc = $user_svc;
        $this->client_svc = $client_svc;
    }

    public function index()
    {
        $args = [
            'module' => 'default',
            'apps_available' => ['0' => 'Select an App']
        ];

        /**
         *  Steps
         *  1. Get user's authorized apps
         */
        $apps_available = [];
        $clients = $this->client_svc->getAllClients();

        foreach($clients as $client)
        {
            $features = $this->client_svc->getAllFeaturesForClient('messaging', $client['id']);

            if(count($features) > 0)
            {
                foreach($features as $feature)
                {
                    $args['apps_available'][$feature['id']] = $client['name'].' '.$feature['feature'];
                }

            }
        }

        $user = $this->user_svc->getUserRecordAndRole();
        $args['menu_options'] = $this->user_svc->getDashMenuOptions($user['roles']);

        return view('messaging.index', $args);
    }

    public function manage($app_id, PushNotificationsService $push_svc)
    {
        $args = [
            'module' => 'showtable',
        ];

        //  Verify the user can view the client the app belongs too or <unauthorized>
        $feature = $this->client_svc->getClientFeature($app_id);

        if(!$feature || ($feature && $feature->page != 'messaging'))
        {
            $args['module'] = 'unauthorized';
        }
        else
        {
            $args['mobile_users'] = $this->getMobileUsers($feature);

            $args['fields'] = [
                'selected'=> [
                    'label' => '✔',
                    'class' => 'checking dumb'
                ],
                'first_name' => [
                    'label' => 'First Name',
                    'class' => 'rname dumb',
                    'sortable' => true
                ],
                'last_name' => [
                    'label' => 'Last Name',
                    'class' => 'rname dumb',
                    'sortable' => true
                ],
                'member_id' => [
                    'label' => 'Member ID',
                    'class' => 'no-mobile dumb',
                    'sortable' => true
                ],
                'primary_location_name' => [
                    'label' => 'Club',
                    'class' => 'no-mobile dumb',
                    'sortable' => true
                ],
                'last_login_readable' => [
                    'label' => 'Last Login',
                    'class' => 'no-mobile dumb',
                    'sortable' => true
                ]

            ];

            //  get available templates
            $args['note_templates'] = $push_svc->getTemplates($feature->client_id);
        }

        $user = $this->user_svc->getUserRecordAndRole();
        $args['menu_options'] = $this->user_svc->getDashMenuOptions($user['roles']);

        return view('messaging.index', $args);
    }

    public function test_push_expo_notification()
    {
        $results = ['success' => false, 'reason' => "User not found..."];

        $user = Users::find(309);
        //$user = Users::find(16577);

        if(!is_null($user))
        {
            try {
                $status = $user->notify(new WereGladYoureHere());
                $results = ['success' => true];
            }
            catch (\Exception $e)
            {
                $results['reason'] = $e->getMessage();
            }
        }

        return response($results, 200);
    }

    public function firePushNotes(FirePushNote $action)
    {
        $results = $action->fire($this->request->all());

        return response()->json($results);
    }

    private function getMobileUsers($feature)
    {
        $results = [];
        // Get the client's data module
        $client = $this->client_svc->getClient($feature->client_id);
        $client_module = $this->client_svc->getClientDataModule($client->uuid, $client);

        // Run the module's getMobileUsers(default) function
        $mobile_users = $client_module->getMobileUsers();

        if(count($mobile_users) > 0)
        {
            date_default_timezone_set('America/New_York');
            foreach($mobile_users as $idx => $mu)
            {
                $mu['primary_location_name'] = is_null($mu['primary_location_name']) ? $mu['home_club_id'] : $mu['primary_location_name'];
                $mu['selected'] = false;
                $mu['last_login_readable'] = date('m/d/Y h:i:s', $mu['last_login']);
                $mobile_users[$idx] = $mu;
            }

            $results = $mobile_users;
        }

        return $results;
    }
}
