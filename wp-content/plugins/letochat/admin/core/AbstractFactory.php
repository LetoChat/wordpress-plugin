<?php

namespace Letochat\AdminView\Core;

abstract class AbstractFactory
{
    final public function getConfig()
    {
        $className = sprintf('\%s\Config\Config', $this->getPluginName());

        return new $className();
    }

    private function getPluginName()
    {
        $parts = explode('/', PLUGIN_LETOCHAT_SLUG);

        $pluginDirParts = explode('-', $parts[0]);

        $name = '';

        foreach ($pluginDirParts as $pluginDirPart) {
            $name .= ucfirst($pluginDirPart);
        }

        return $name;
    }
}
