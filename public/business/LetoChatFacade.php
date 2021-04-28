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

    /**
     * @param $cart_item_key
     * @param $product_id
     * @param $quantity
     * @param $variation_id
     * @param $variation
     * @param $cart_item_data
     */
    public function addToCartEvent($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
    {
        $this->getFactory()->createWidget()->addToCartEvent($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data);
    }

    /**
     * @param $product_id
     */
    public function sessionStoreForProductAdded($product_id)
    {
        $this->getFactory()->createWidget()->sessionStoreForProductAdded($product_id);
    }

    public function updateChatTokenAjaxBehavior()
    {
        $this->getFactory()->createWidget()->updateChatTokenAjaxBehavior();
    }

    public function registerApiRoutes()
    {
        if ($this->woocommerceIsActivated() === true) {
            $this->getFactory()->createOrderApi()->registerRoutes();
            $this->getFactory()->createUserCartApi()->registerRoutes();
        }
    }
}
