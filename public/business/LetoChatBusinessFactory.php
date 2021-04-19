<?php

namespace LetoChat\PublicView\Business;

use LetoChat\Includes\LetoChatHelper;
use LetoChat\Includes\PluginResponse;
use LetoChat\PublicView\Business\Model\Api\Order;
use LetoChat\PublicView\Business\Model\Api\UserCart;
use LetoChat\PublicView\Business\Model\Widget;
use LetoChat\PublicView\Core\AbstractFactory;
use \LetoChat\Widget as GenericLetoChatWidget;

class LetoChatBusinessFactory extends AbstractFactory
{
    use LetoChatHelper;

    public function createPluginResponse()
    {
        return new PluginResponse();
    }

    public function createWidget()
    {
        return new Widget($this->getConfig(), $this->createGenericLetoChatWidget());
    }

    public function createOrderApi()
    {
        return new Order($this->getConfig());
    }

    public function createUserCartApi()
    {
        return new UserCart($this->getConfig(), $this->getRepository());
    }

    private function createGenericLetoChatWidget()
    {
        $settingsOptions = $this->getConfig()->getSettingsOptions();

        $channelId = $this->get_option($settingsOptions['channel_id'], $this->getConfig()->getLetoChatEncryptKey());
        $channelSecret = $this->get_option($settingsOptions['channel_secret'], $this->getConfig()->getLetoChatEncryptKey());

        return new GenericLetoChatWidget($channelId, $channelSecret);
    }
}
