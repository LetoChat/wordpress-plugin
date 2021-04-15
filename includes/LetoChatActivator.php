<?php

namespace LetoChat\Includes;

use \Defuse\Crypto\Key;

class LetoChatActivator
{
	public static function activate()
    {
        update_option(PLUGIN_LETO_CHAT_SETTINGS_OPTIONS['enable_widget'], 'on', 'yes');
        update_option(PLUGIN_LETO_CHAT_SETTINGS_OPTIONS['visible_for_admins'], 'off', 'yes');

        $key = get_option(PLUGIN_LETO_CHAT_SETTINGS_OPTIONS['enc_key'], false);

        if (empty($key)) {
            $key = Key::createNewRandomKey()->saveToAsciiSafeString();

            update_option(PLUGIN_LETO_CHAT_SETTINGS_OPTIONS['enc_key'], $key);
        }
	}
}
