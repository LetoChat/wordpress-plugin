<?php

namespace LetoChat\PublicView\Business;

use LetoChat\Includes\LetoChatHelper;
use LetoChat\Includes\PluginResponse;
use LetoChat\PublicView\Business\Model\Api\Order;
use LetoChat\PublicView\Business\Model\Api\OrderInterface;
use LetoChat\PublicView\Business\Model\Api\UserCart;
use LetoChat\PublicView\Business\Model\Api\UserCartInterface;
use LetoChat\PublicView\Business\Model\Widget;
use LetoChat\PublicView\Business\Model\WidgetInterface;
use LetoChat\PublicView\Core\AbstractFactory;
use \LetoChat\Widget as GenericLetoChatWidget;

class LetoChatBusinessFactory extends AbstractFactory
{
    use LetoChatHelper;

    /**
     * @return WidgetInterface
     */
    public function createWidget()
    {
        return new Widget(
            $this->getConfig(),
            $this->createGenericLetoChatWidget(),
            $this->createPluginResponse()
        );
    }

    /**
     * @return OrderInterface
     */
    public function createOrderApi()
    {
        return new Order($this->getConfig());
    }

    /**
     * @return UserCartInterface
     */
    public function createUserCartApi()
    {
        return new UserCart($this->getConfig(), $this->getRepository());
    }

    /**
     * @return GenericLetoChatWidget
     */
    protected function createGenericLetoChatWidget()
    {
        $settingsOptions = $this->getConfig()->getSettingsOptions();

        $channelId = $this->get_option($settingsOptions['channel_id'], $this->getConfig()->getLetoChatEncryptKey());
        $channelSecret = $this->get_option($settingsOptions['channel_secret'], $this->getConfig()->getLetoChatEncryptKey());

        return new GenericLetoChatWidget($channelId, $channelSecret);
    }

    /**
     * @return PluginResponse
     */
    protected function createPluginResponse()
    {
        return new PluginResponse();
    }
}
