<?php

namespace App\Modules;

use App\Repositories\BaseRepository;
use App\ExternalModels\Fitness360\mySQL\Leads;

class Fitness360DataModule
{
    protected $client_id = '4ac59443-40de-48a6-b070-e3cd982e0378';
    protected $fit360Repo;

    public function __construct()
    {
        $this->fit360Repo = [
            'web' => [
                'leads' => new BaseRepository(new Leads()),
            ]
        ];
    }

    public function getModuleReports()
    {
        // @todo - be sure to make this db driven
        return [
            [
                'uuid' => 'dfc1108f-f556-4255-afff-6cce4b99c57e',
                'name' => 'Facebook Ads Leads',
                'url' => "reports/fitness360/facebook-leads"
            ]
        ];
    }
}
