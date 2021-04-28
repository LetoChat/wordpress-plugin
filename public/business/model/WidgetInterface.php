<?php

namespace LetoChat\PublicView\Business\Model;

interface WidgetInterface
{
    public function addScript();

    /**
     * @param $cart_item_key
     * @param $product_id
     * @param $quantity
     * @param $variation_id
     * @param $variation
     * @param $cart_item_data
     */
    public function addToCartEvent($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data);

    /**
     * @param $product_id
     */
    public function sessionStoreForProductAdded($product_id);

    public function updateChatTokenAjaxBehavior();

}