<?php

namespace LetoChat\PublicView\Business\Model;

use LetoChat\Config\AbstractConfigInterface;
use LetoChat\Includes\LetoChatHelper;
use \LetoChat\Widget as GenericLetoChatWidget;
use \WC_Session_Handler;
use \WC_Customer;

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

        $infoValues = [];

        if ($this->woocommerceIsActivated() === true) {
            $infoValues['logged'] = false;

            if (is_user_logged_in() === true) {
                $infoValues['logged'] = true;

                $currentUserId = get_current_user_id();
                $currentUserData = new WC_Customer($currentUserId);

                $infoValues['name'] = sprintf('%s %s', $currentUserData->get_first_name(), $currentUserData->get_last_name());
                $infoValues['avatar'] = $currentUserData->get_avatar_url();
                $infoValues['email'] = $currentUserData->get_billing_email();
                $infoValues['phone'] = $currentUserData->get_billing_phone();
                $infoValues['company_name'] = $currentUserData->get_billing_company();
            }

            if ($this->getUserId() !== 0) {
                $infoValues['id'] = $this->getUserId();
            }
        }

        try {
            $chat->infoValues($infoValues);

            echo $chat->build();
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo '';
        }
    }

    /**
     * @param $isVisible
     * @return bool
     */
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

    /**
     * @return int|mixed
     */
    private function getUserId()
    {
        if (is_user_logged_in() === true) {
            return get_current_user_id();
        }

        $wcSessionHandler = new WC_Session_Handler();
        $guestSession = $wcSessionHandler->get_session_cookie();

        if ($guestSession === false) {
            return 0;
        }

        return $guestSession[0];
    }
}