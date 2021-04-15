<?php

namespace LetoChat\PublicView\Core;

abstract class AbstractFacade
{
    final public function getFactory()
    {
        $className = sprintf('\%s\PublicView\Business\%sBusinessFactory', 'LetoChat', 'LetoChat');

        return new $className();
    }

    final public function getEntityManager()
    {
        $className = sprintf('\%s\PublicView\Persistence\%sEntityManager', 'LetoChat', 'LetoChat');

        return new $className();
    }

    final public function getRepository()
    {
        $className = sprintf('\%s\PublicView\Persistence\%sRepository', 'LetoChat', 'LetoChat');

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
