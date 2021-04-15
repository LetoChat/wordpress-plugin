<?php

namespace LetoChat\Includes;

use stdClass;

class PluginResponse
{
    private $pluginResponse;

    public function __construct()
    {
        $this->pluginResponse = new stdClass();
    }
}
