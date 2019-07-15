<?php

namespace App\Widgets\TruFit\Enrollments;

use App\Widgets\BaseWidget;
use Ixudra\Curl\Facades\Curl;
use App\ExternalModels\TruFit\mySQL\Conversions;

class TotalEnrollments extends BaseWidget
{
    public function __construct()
    {
        parent::__construct('Total Free Pass Leads');
    }

    public function collect()
    {
        $results = [];

        $enrollments = $this->getEnrollments();

        $results['count'] = $enrollments ? $enrollments : 0;
        $results['vue_component'] = '<cool-counter-component :value="'.$enrollments.'" :name="\''.$this->getWidgetName().'\'"></cool-counter-component>';

        return $results;
    }

    private function getEnrollments()
    {
        $results = false;

        $response = Curl::to('https://tfapi.capeandbay.com/members/conversions')
            ->asJson(true)
            ->get();

        if($response)
        {
            $number = $response['number'];
            $results = $number;
        }

        return $results;
    }
}
