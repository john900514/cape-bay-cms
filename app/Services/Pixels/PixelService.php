<?php

namespace App\Services\Pixels;

use App\Pixels;
use App\PixelActivityLog;

class PixelService
{
    protected $pixels, $pixel_activity;

    public function __construct(Pixels $p, PixelActivityLog $pa)
    {
        $this->pixels = $p;
        $this->pixel_activity = $pa;
    }

    public function getPixelModel()
    {
        return $this->pixels;
    }

    public function getPixelActivityModel()
    {
        return $this->pixel_activity;
    }
}
