<?php

namespace LetoChat\AdminView\Business\Model;

use LetoChat\Includes\LetoChatHelper;
use \LetoChat\Connector;

class AdminPage implements AdminPageInterface
{
    use LetoChatHelper;

    public function __construct() {}

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
        $file = sprintf("%sadmin/presentation/admin-page.php", PLUGIN_LETO_CHAT_PATH);
    
        if (file_exists($file) === false) {
            echo '';
        }

        echo $this->renderView($file, []);
    }

    public function checkLetoChatData()
    {
        check_ajax_referer('ajax_letochat_public', 'security');

        $keyChannel = wp_kses($_POST['key_channel'], []);
        $authenticationSecret = wp_kses($_POST['authentication_secret'], []);
        $privateKey = wp_kses($_POST['private_key'], []);

        // Check connection to LetoChat platform

        die();
    }
}
