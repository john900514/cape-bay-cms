<?php

namespace App\Services;

use Bouncer;
use App\Widgets;
use App\Clients;

class WidgetService
{
    protected $widgets, $clients;

    public function __construct(Widgets $widgets, Clients $c)
    {
        $this->widgets = $widgets;
        $this->clients = $c;
    }

    public function getAllWidgets()
    {
        $results = [];

        $widgets = $this->widgets->where('active', '=', 1)
            ->get();

        if (count($widgets) > 0) {
            foreach ($widgets as $record) {

                $client = Clients::find($record->client_id);
                //Bouncer::allow(backpack_user())->to('view', $client);
                if(backpack_user()->can('view', $client))
                {
                    $widget = new $record->class_name();
                    $data = $widget->collect();
                    $results[$client->name][] = [
                        'name' => $widget->getWidgetName(),
                        'amt_leads' => $data['count'],
                        'vue_component' => $data['vue_component']
                    ];
                }
            }
        }

        return $results;
    }
}
