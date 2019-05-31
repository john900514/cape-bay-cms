<?php
namespace App\Modules;

use App\Repositories\BaseRepository;
use App\ExternalModels\Morning\mySQL\Leads;
use App\ExternalModels\Morning\mySQL\Conversions;

class MorningFitnessDataModule
{
    protected $client_id = '6bf50eef-cdb8-4d4d-91b9-8ffb9962ca32';
    protected $morningRepo;

    public function __construct()
    {
        $this->morningRepo = [
            'web' => [
                'leads' => new BaseRepository(new Leads()),
                'conversions' => new BaseRepository((new Conversions())),
            ]
        ];
    }

    public function getModuleReports()
    {
        // @todo - be sure to make this db driven
        return [
            [
                'uuid' => 'dfc1108f-f556-4255-afff-6cce4b99c57e',
                'name' => 'Payment Form Leads',
                'url' => "reports/morning/payment-leads"
            ],
            [
                'uuid' => 'd5405adc-952a-48a0-a0d8-47cfcdd88f8d',
                'name' => 'Payment Form Conversions',
                'url' => "reports/morning/payment-conversions"
            ]
        ];
    }
}
