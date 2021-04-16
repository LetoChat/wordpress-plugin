<?php

namespace LetoChat\PublicView\Business;

use LetoChat\Includes\PluginResponse;
use LetoChat\PublicView\Business\Model\Api\Order;
use LetoChat\PublicView\Business\Model\Api\UserCart;
use LetoChat\PublicView\Business\Model\Widget;
use LetoChat\PublicView\Core\AbstractFactory;

class LetoChatBusinessFactory extends AbstractFactory
{
    public function createPluginResponse()
    {
        return new PluginResponse();
    }

    public function createWidget()
    {
        return new Widget($this->getConfig());
    }

    public function createOrderApi()
    {
        return new Order($this->createPluginResponse());
    }

    public function createUserCartApi()
    {
        return new UserCart($this->getConfig(), $this->getRepository());
    }
}
