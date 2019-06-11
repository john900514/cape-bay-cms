<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\UserMgntService;
use App\Services\ClientMgntService;
use App\Http\Controllers\Controller;

class RepoController extends Controller
{
    protected $request, $client_svc;

    public function __construct(Request $request, ClientMgntService $client_svc)
    {
        $this->request = $request;
        $this->client_svc = $client_svc;
    }

    public function index(UserMgntService $user_svc)
    {
        $args = [];
        /**
         * Steps
         * 1. Get the logged in user
         * 2. Get a List of all the client and curate data for a dropdown
         * 3. Pass Along to the view
         */
        $user = $user_svc->getUserRecordAndRole();
        $args['menu_options'] = $user_svc->getDashMenuOptions($user['roles']);
        $clients = $this->client_svc->getAllClients();
        $final_clients = [];
        foreach($clients as $client)
        {
            $features = $this->client_svc->getAllFeaturesForClient('data-repo', $client['id']);

            if(count($features) > 0)
            {
                $final_clients[$client['uuid']] = $client['name'];
            }
        }

        $args['clients'] = array_merge(['Select a Client'], $final_clients);

        return view('repository.index', $args);
    }

    public function get_client_data_store(UserMgntService $user_svc)
    {
        $args = [];

        $user = $user_svc->getUserRecordAndRole();
        $args['menu_options'] = $user_svc->getDashMenuOptions($user['roles']);

        $args['clubs'] = [];
        /**
         * Steps
         * 1. Get all the stores in alpha order
         */
        $pathsplode = explode('/',$this->request->path());
        $el_max = count($pathsplode) - 1;
        $d_store = $pathsplode[$el_max];
        $client_name = $pathsplode[$el_max - 1];

        switch($client_name)
        {
            case 'trufit':
                $uuid = '43d798ee-3247-4749-90a4-346b41d3e745';
                break;

            default:
                //Cape & Bay
                //$uuid = '4c656c3a-e771-418e-9d11-16d9386bc2fa';

        }

        $client = $this->client_svc->getClient($uuid);

        if($client)
        {
            $client_module = $this->client_svc->getClientDataModule($uuid, $client);

            if($client_module)
            {
                $clubs_model = $client_module->getStoresModel();
                $clubs = $clubs_model->where('RegionCode', '=', 'TRUX')
                    ->where('active', '=', 1)
                    ->orderBy('ClubName', 'ASC')
                    ->with('promo_codes')
                    ->get();

                if(count($clubs) > 0)
                {
                    $final_clubs = [
                        'dropdown' => ['Select an Athletic Club'],
                        'clubs' => []
                    ];
                    foreach ($clubs as $club)
                    {
                        $final_clubs['dropdown'][$club->ClubId] = $club->ClubName;
                        $final_clubs['clubs'][$club->ClubId] = $club->toArray();
                    }

                    $args['clubs'] = $final_clubs;
                }
                else
                {
                    return redirect('cms/repo');
                }

            }
            else
            {
                return redirect('cms/repo');
            }
        }
        else
        {
            return redirect('cms/repo');
        }

        return view('repository.'.$d_store.'.index', $args);
    }

    public function get_client_data_stores($client_uuid)
    {
        $results = ['success' => false, 'reason' => 'Unknown Client UUID'];

        $client = $this->client_svc->getClient($client_uuid);

        if($client)
        {
            $client_module = $this->client_svc->getClientDataModule($client_uuid, $client);

            if($client_module)
            {
                /**
                 * Steps
                 * 1. call the client_module get module data stores
                 */
                $data_stores = $client_module->getModuleDataStores ();

                if($data_stores && is_array($data_stores) && count($data_stores) > 0)
                {
                    $results = [
                        'success' => true,
                        'data_stores' => $data_stores
                    ];
                }
                else
                {
                    $results['reason'] = 'No data stores for this client are available';
                }
            }
            else
            {
                $results['reason'] = 'Missing Client Module. Ask your dev, what\'s really good.';
            }
        }

        return response($results, 200);
    }
}
