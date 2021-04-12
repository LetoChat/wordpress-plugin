<?php

namespace LetoChat\AdminView\Business;

use LetoChat\AdminView\Core\AbstractFacade;

class LetoChatFacade extends AbstractFacade implements LetoChatFacadeInterface
{
    protected static $instance = null;

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function adminMenu()
    {
        $this->getFactory()->createAdminPage()->adminMenu();
    }
}
