<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserMgntService;
use App\Services\ClientMgntService;

class SettingsController extends Controller
{
    protected $request, $client_svc;

    public function __construct(Request $request, ClientMgntService $client_svc)
    {
        $this->request = $request;
        $this->client_svc = $client_svc;
    }

    public function index()
    {
        $args = [
            'page_name' => ''
        ];

        return view('settings.generic-settings', $args);
    }

    public function admin_menu(UserMgntService $user_svc)
    {
        $args = [
            'page_name' => 'Admin',
            'menu_options' => [],
        ];

        $user = $user_svc->getUserRecordAndRole();
        $args['menu_options'] = $user_svc->getAdminMenuOptions($user['roles']);

        return view('settings.generic-settings', $args);
    }

    public function admin_records_mgnt()
    {
        $args = [
            'module' => 'default',
            'clients' => []
        ];
        $clients = $this->client_svc->getAllClients();
        $final_clients = [];
        foreach($clients as $client)
        {
            $final_clients[$client['uuid']] = $client['name'];
        }
        $args['clients'] = array_merge(['Select a Client'], $final_clients);

        return view('settings.admin.records', $args);
    }

    public function admin_get_records_repo_links($uuid)
    {
        $results = ['success' => false, 'reason' => 'Unknown Client UUID'];

        $client = $this->client_svc->getClient($uuid);

        // The UUID needs to be a valid record or fail
        if($client)
        {
            $client_module = $this->client_svc->getClientDataModule($uuid);

            if($client_module)
            {
                $repos = $client_module->getModuleRepos();

                if($repos && is_array($repos) && count($repos) > 0)
                {
                    $results = [
                        'success' => true,
                        'repos' => $repos
                    ];
                }
                else
                {
                    $results['reason'] = 'No repos available';
                }
            }
            else
            {
                $results['reason'] = 'Missing Client Module. Ask your dev, what\'s really good.';
            }
        }

        return response($results, 200);
    }

    public function admin_show_records_repo($uuid, $repo)
    {
        $args = [
            'module' => 'loadRepo',
            'repoData' => []
        ];

        $client = $this->client_svc->getClient($uuid);

        if($client)
        {
            $client_module = $this->client_svc->getClientDataModule($uuid);

            if($client_module)
            {
                $repo = $client_module->getModuleRepo($repo);

                if($repo)
                {
                    $args['repoData'] = $repo;
                }
            }
        }

        return view('settings.admin.records', $args);
    }
}
