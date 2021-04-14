<?php

namespace LetoChat\Includes;

class LetoChatActivator
{
	public static function activate()
    {
        update_option(PLUGIN_LETO_CHAT_SETTINGS_OPTIONS['enable_widget'], 'on', 'yes');
        update_option(PLUGIN_LETO_CHAT_SETTINGS_OPTIONS['visible_for_admins'], 'off', 'yes');
	}
}
