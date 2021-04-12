<?php

namespace Letochat\PublicView\Core;

abstract class AbstractFacade
{
    final public function getFactory()
    {
        $className = sprintf('\%s\PublicView\Business\%sBusinessFactory', $this->getPluginName(), $this->getPluginName());

        return new $className();
    }

    final public function getEntityManager()
    {
        $className = sprintf('\%s\PublicView\Persistence\%sEntityManager', $this->getPluginName(), $this->getPluginName());

        return new $className();
    }

    final public function getRepository()
    {
        $className = sprintf('\%s\PublicView\Persistence\%sRepository', $this->getPluginName(), $this->getPluginName());

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
