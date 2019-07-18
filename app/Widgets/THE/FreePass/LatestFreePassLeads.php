<?php


namespace App\Widgets\THE\FreePass;

use App\Widgets\BaseWidget;
use App\ExternalModels\THE\mySQL\Leads;

class LatestFreePassLeads extends BaseWidget
{
    public function __construct()
    {
        parent::__construct('Free Pass - Last 24 Hours');
    }

    public function collect()
    {
        $results = [];

        $leads = $this->getLeads();

        $results['count'] = $leads ? count($leads) : 0;
        $results['vue_component'] = '<cool-counter-component :value="' . $results['count'] . '" :name="\'' . $this->getWidgetName() . '\'"></cool-counter-component>';

        return $results;
    }

    private function getLeads()
    {
        $results = false;

        $model = new Leads();
        $leads = $model->getLatestLeads('free-pass');

        if ($leads) {
            $results = $leads->toArray();
        }

        return $results;
    }
}
