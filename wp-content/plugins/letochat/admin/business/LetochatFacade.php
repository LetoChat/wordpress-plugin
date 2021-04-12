<?php

namespace Letochat\AdminView\Business;

use Letochat\AdminView\Core\AbstractFacade;

class LetochatFacade extends AbstractFacade implements LetochatFacadeInterface
{
    protected static $instance = null;

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }
}
