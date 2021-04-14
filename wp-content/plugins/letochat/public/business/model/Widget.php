<?php

namespace LetoChat\PublicView\Business\Model;

use LetoChat\Config\AbstractConfigInterface;
use LetoChat\Includes\LetoChatHelper;
use \LetoChat\Widget as GenericLetoChatWidget;

class Widget implements WidgetInterface
{
    use LetoChatHelper;

    private $config;

    public function __construct(AbstractConfigInterface $config)
    {
        $this->config = $config;
    }

    public function addScript()
    {
        $settingsOptions = $this->config->getSettingsOptions();

        $isEnabled = get_option($settingsOptions['enable_widget']);

        if ($isEnabled === 'off') {
            return;
        }

        $isVisibleForAdmins = get_option($settingsOptions['visible_for_admins']);

        if ($this->hideForAdmins($isVisibleForAdmins) === true) {
            return;
        }

        $channelId = $this->get_option($settingsOptions['channel_id'], $this->config->getLetoChatEncryptKey());
        $channelSecret = $this->get_option($settingsOptions['channel_secret'], $this->config->getLetoChatEncryptKey());

        $chat = new GenericLetoChatWidget($channelId, $channelSecret);

        try {
            $chat->infoValues([
                'name' => 'Ion Popescu',
                'email' => 'ion.popescu@gmail.com',
            ])->customValues([
                'Client type' => 'Silver',
                'Client code' => '0456785',
            ]);

            echo $chat->build();
        } catch (\Exception $e) {
            echo '';
        }
    }

    private function hideForAdmins($isVisible)
    {
        if (is_user_logged_in() === true) {
            $user = wp_get_current_user();

            $roles = $user->roles;

            if (in_array('administrator', $roles) === true && $isVisible === 'off') {
                return true;
            }
        }

        return false;
    }
}