<?php

namespace LetoChat\AdminView\Business\Model;

use LetoChat\Includes\LetoChatHelper;
use \LetoChat\Connector;

class AdminPage implements AdminPageInterface
{
    use LetoChatHelper;

    private $pluginResponse;

    private $config;

    public function __construct($pluginResponse, $config) {
        $this->pluginResponse = $pluginResponse;
        $this->config = $config;
    }

    public function adminMenu()
    {
        add_menu_page(
			__('LetoChat', 'letochat'),
			__('LetoChat', 'letochat'),
			'manage_options',
			'letochat',
			[$this, 'adminPageContent'],
            PLUGIN_LETO_CHAT_URL . '/images/menu-logo.png',
			3
		);
    }

    public function adminPageContent()
    {
        $settingsOptions = $this->config->getSettingsOptions();

        $file = sprintf("%sadmin/presentation/admin-page.php", PLUGIN_LETO_CHAT_PATH);

        if (file_exists($file) === false) {
            echo '';
        }

        echo $this->renderView($file, [
            'channel_id' => $this->get_option($settingsOptions['channel_id'], $this->config->getLetoChatEncryptKey()),
            'channel_secret' => $this->get_option($settingsOptions['channel_secret'], $this->config->getLetoChatEncryptKey()),
            'auth_secret' => $this->get_option($settingsOptions['auth_secret'], $this->config->getLetoChatEncryptKey()),
            'is_connected' => get_option($settingsOptions['is_connected']),
            'enable_widget' => get_option($settingsOptions['enable_widget']),
            'visible_for_admins' => get_option($settingsOptions['visible_for_admins']),
        ]);
    }

    public function connectToLetoChat()
    {
        check_ajax_referer('ajax_letochat_public', 'security');

        $settingsOptions = $this->config->getSettingsOptions();

        update_option($settingsOptions['is_connected'], false, 'yes');
        update_option($settingsOptions['channel_id'], '', 'yes');
        update_option($settingsOptions['channel_secret'], '', 'yes');
        update_option($settingsOptions['auth_secret'], '', 'yes');

        $channelID = wp_kses($_POST['channel_id'], []);
        $channelSecret = wp_kses($_POST['channel_secret'], []);
        $authSecret = wp_kses($_POST['auth_secret'], []);

        $connector = new Connector($channelID, $channelSecret, $authSecret, []);

//        if ($connector->check() === false) {
//            $this->pluginResponse->isSuccess = false;
//            $this->pluginResponse->message = $connector->getError();
//
//            echo json_encode($this->pluginResponse);
//
//            die();
//        }

        if ($connector->connect() === false) {
            $this->pluginResponse->isSuccess = false;
            $this->pluginResponse->message = $connector->getError();

            echo json_encode($this->pluginResponse);

            die();
        }

        $this->pluginResponse->isSuccess = true;
        $this->pluginResponse->message = __('The connection was made successfully!', 'letochat');

        update_option($this->config->getSettingsOptions()['is_connected'], true, 'yes');
        $this->update_option($settingsOptions['channel_id'], $channelID, $this->config->getLetoChatEncryptKey(), 'yes');
        $this->update_option($settingsOptions['channel_secret'], $channelSecret, $this->config->getLetoChatEncryptKey(), 'yes');
        $this->update_option($settingsOptions['auth_secret'], $authSecret, $this->config->getLetoChatEncryptKey(), 'yes');

        echo json_encode($this->pluginResponse);

        die();
    }

    public function switcherAjaxCall()
    {
        check_ajax_referer('ajax_letochat_public', 'security');

        $switcherType = wp_kses($_POST['type'], []);
        $switcherStatus = wp_kses($_POST['status'], []);

        $settingsOptions = $this->config->getSettingsOptions();

        if ($switcherType === 'enable_widget') {
            $this->updateSwitcherByType($settingsOptions['enable_widget'], $switcherStatus);

            if ($switcherStatus === 'off') {
                $this->pluginResponse->message = __('The widget is disabled.', 'letochat');
            } else {
                $this->pluginResponse->message = __('The widget is enabled.', 'letochat');
            }
        } elseif ($switcherType === 'visible_for_admins') {
            $this->updateSwitcherByType($settingsOptions['visible_for_admins'], $switcherStatus);

            if ($switcherStatus === 'off') {
                $this->pluginResponse->message = __('The widget is not visible for admins.', 'letochat');
            } else {
                $this->pluginResponse->message = __('The widget is visible for admins.', 'letochat');
            }
        }

        $this->pluginResponse->isSuccess = true;

        echo json_encode($this->pluginResponse);

        die();
    }

    private function updateSwitcherByType($option, $value)
    {
        $isUpdated = update_option($option, $value, 'yes');

        if ($isUpdated === false) {
            $this->pluginResponse->isSuccess = false;
            $this->pluginResponse->message = __('Something went wrong, please try again.', 'letochat');

            echo json_encode($this->pluginResponse);

            die();
        }
    }
}
