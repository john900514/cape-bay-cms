<?php

namespace App\Widgets\TruFit\Enrollments;

use App\Widgets\BaseWidget;
use Ixudra\Curl\Facades\Curl;
use App\ExternalModels\TruFit\mySQL\Conversions;

class TotalLast24Hours extends BaseWidget
{
    public function __construct()
    {
        parent::__construct('Total Enrollments (Last 24 Hrs)');
    }

    public function collect()
    {
        $results = [];

        $enrollments = $this->getEnrollments();

        $results['count'] = count($enrollments) > 0 ? count($enrollments) : 0;
        $results['vue_component'] = '<cool-counter-component :value="'.count($enrollments).'" :name="\''.$this->getWidgetName().'\'"></cool-counter-component>';

        return $results;
    }

    private function getEnrollments()
    {
        $results = false;

        $model = new Conversions();
        $enrollments = $model->getLatestEnrollments();

        if ($enrollments) {
            $results = $enrollments->toArray();
        }

        return $results;
    }
}
