<?php

namespace App\Http\Controllers;

use App\Services\ClientMgntService;
use Illuminate\Http\Request;
use App\Services\UserMgntService;

class ReportingController extends Controller
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
            'module' => 'default'
        ];

        $clients = $this->client_svc->getAllClients();
        $final_clients = [];
        foreach($clients as $client)
        {
            $final_clients[$client['uuid']] = $client['name'];
        }

        $args['clients'] = array_merge(['Select a Client'], $final_clients);

        return view('reporting.reportsdash', $args);
    }

    public function get_client_reports($uuid)
    {
        $results = ['success' => false, 'reason' => 'Unknown Client UUID'];

        $client = $this->client_svc->getClient($uuid);

        // The UUID needs to be a valid record or fail
        if($client)
        {
            $client_module = $this->client_svc->getClientDataModule($uuid);

            if($client_module)
            {
                // @todo - use the $client_module to get a list of available reports.
                $reports = $client_module->getModuleReports();

                if($reports && is_array($reports) && count($reports) > 0)
                {
                    $results = [
                        'success' => true,
                        'reports' => $reports
                    ];
                }
                else
                {
                    $results['reason'] = 'No reports available';
                }
            }
            else
            {
                $results['reason'] = 'Missing Client Module. Ask your dev, what\'s really good.';
            }
        }

        return response($results, 200);

    }

    public function get_client_report($uuid, $report)
    {
        $args = [
            'module' => 'showReport',
            'report' => []
        ];

        $client = $this->client_svc->getClient($uuid);

        // The UUID needs to be a valid record or fail
        if($client)
        {
            $client_module = $this->client_svc->getClientDataModule($uuid);

            if($client_module)
            {
                // @todo - use the $client_module to get a list of available reports.
                $reportResults = $client_module->getModuleReport($report);

                if($reportResults && is_array($reportResults) && count($reportResults) > 0)
                {
                    $args['report'] = $reportResults;
                }
            }
            else
            {
                $results['reason'] = 'Missing Client Module. Ask your dev, what\'s really good.';
            }
        }

        return view('reporting.reportsdash', $args);
    }

    public function tracking_menu()
    {
        $args = [];

        $clients = $this->client_svc->getAllClients(); // Get a list of all the clients the user can access;

        $final_clients = [];
        foreach($clients as $client)
        {
            // check client features to see if the client supports any feature inside menu options
            $features = $this->client_svc->getAllFeaturesForClient('live-tracking', $client['id']);

            if(count($features) > 0)
            {
                $final_clients[$client['uuid']] = $client['name'];
            }
        }

        $args['clients'] = array_merge(['Please Select a Client'], $final_clients);

        return view('reporting.tracking', $args);

        //$menu_options = $this->user_svc->getLiveTrackMenuOptions($user['roles']); // Get a list of all Live Track Options
    }
}
