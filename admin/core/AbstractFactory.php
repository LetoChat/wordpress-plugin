<?php

namespace LetoChat\AdminView\Core;

abstract class AbstractFactory
{
    final public function getConfig()
    {
        $className = sprintf('\%s\Config\Config%s', 'LetoChat', ucfirst(PLUGIN_LETO_CHAT_ENVIRONMENT_TYPE));

        return new $className();
    }

    final public function getEntityManager()
    {
        $className = sprintf('\%s\AdminView\Persistence\%sEntityManager', 'LetoChat', 'LetoChat');

        return new $className();
    }

    final public function getRepository()
    {
        $className = sprintf('\%s\AdminView\Persistence\%sRepository', 'LetoChat', 'LetoChat');

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
