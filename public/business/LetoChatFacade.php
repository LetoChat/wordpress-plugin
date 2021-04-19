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

    public function addToCartEvent($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
    {
        $this->getFactory()->createWidget()->addToCartEvent($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data);
    }

    public function registerApiRoutes()
    {
        if ($this->woocommerceIsActivated() === true) {
            $this->getFactory()->createOrderApi()->registerRoutes();
            $this->getFactory()->createUserCartApi()->registerRoutes();
        }
    }
}
