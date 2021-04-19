<?php

namespace LetoChat\PublicView\Business;

interface LetoChatFacadeInterface
{
    public function addScript();

    public function addToCartEvent($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data);

    public function registerApiRoutes();
}
