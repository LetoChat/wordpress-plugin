<?php

namespace LetoChat\AdminView\Business;

use LetoChat\AdminView\Core\AbstractFactory;
use LetoChat\AdminView\Business\Model\AdminPage;
use LetoChat\AdminView\Business\Model\AdminPageInterface;
use LetoChat\Includes\PluginResponse;

class LetoChatBusinessFactory extends AbstractFactory
{
    public function createPluginResponse()
    {
        return new PluginResponse();
    }

    public function createAdminPage()
    {
        return new AdminPage(
            $this->createPluginResponse(),
            $this->getConfig()
        );
    }
}
