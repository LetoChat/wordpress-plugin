<?php

namespace Letochat\AdminView\Core;

abstract class AbstractFacade
{
    final public function getFactory()
    {
        $className = sprintf('\%s\AdminView\Business\%sBusinessFactory', $this->getPluginName(), $this->getPluginName());

        return new $className();
    }

    final public function getEntityManager()
    {
        $className = sprintf('\%s\AdminView\Persistence\%sEntityManager', $this->getPluginName(), $this->getPluginName());

        return new $className();
    }

    final public function getRepository()
    {
        $className = sprintf('\%s\AdminView\Persistence\%sRepository', $this->getPluginName(), $this->getPluginName());

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
