<?php

namespace LetoChat\PublicView\Core;

abstract class AbstractFactory
{
    final public function getConfig()
    {
        $className = sprintf('\%s\Config\Config%s', 'LetoChat', ucfirst(PLUGIN_LETO_CHAT_ENVIRONMENT_TYPE));

        return new $className();
    }

    private function getPluginName()
    {
        $parts = explode('/', PLUGIN_LETO_CHAT_SLUG);

        $pluginDirParts = explode('-', $parts[0]);

        $name = '';

        foreach ($pluginDirParts as $pluginDirPart) {
            $name .= ucfirst($pluginDirPart);
        }

        return $name;
    }
}
