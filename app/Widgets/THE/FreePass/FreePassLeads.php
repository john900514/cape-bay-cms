<?php

namespace App\Widgets\THE\FreePass;

use App\Widgets\BaseWidget;
use App\ExternalModels\THE\mySQL\Leads;

class FreePassLeads extends BaseWidget
{
    public function __construct()
    {
        parent::__construct('Total Free Pass Leads');
    }

    public function collect()
    {
        $results = [];

        $leads = $this->getLeads();

        $results['count'] = $leads ? count($leads) : 0;
        $results['vue_component'] = '<cool-counter-component :value="'.$results['count'].'" :name="\''.$this->getWidgetName().'\'"></cool-counter-component>';

        return $results;
    }

    private function getLeads()
    {
        $results = false;

        $model = new Leads();
        $leads = $model->getLeads('free-pass');

        if($leads)
        {
            $results = $leads->toArray();
        }

        return $results;
    }
}
