<?php

namespace App\Widgets;

use App\Widgets;

class BaseWidget
{
    protected $widget_name;

    public function __construct($name)
    {
        $this->widget_name = $name;
    }

    public function getWidgetName()
    {
        return $this->widget_name;
    }
}
