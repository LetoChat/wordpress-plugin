<?php

namespace LetoChat\AdminView\Business\Model;

use LetoChat\Includes\LetoChatHelper;

class AdminPage implements AdminPageInterface
{
    use LetoChatHelper;

    public function __construct() {}

    public function adminMenu()
    {
        add_menu_page(
			__('LetoChat', 'leto-chat'),
			__('LetoChat', 'leto-chat'),
			'manage_options',
			'letochat',
			[$this, 'adminPageContent'],
            PLUGIN_LETO_CHAT_URL . '/images/menu-logo.png',
			3
		);
    }

    public function adminPageContent()
    {
        echo 'test';
    }
}
