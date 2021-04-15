<?php

namespace LetoChat\PublicView\Business;

use LetoChat\Includes\LetoChatHelper;
use LetoChat\PublicView\Core\AbstractFacade;

class LetoChatFacade extends AbstractFacade implements LetoChatFacadeInterface
{
    use LetoChatHelper;

    protected static $instance = null;

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function addScript()
    {
        $this->getFactory()->createWidget()->addScript();
    }

    public function registerApiRoutes()
    {
        if ($this->woocommerceIsActivated() === true) {
            $this->getFactory()->createOrderApi()->registerRoutes();
        }
    }
}
