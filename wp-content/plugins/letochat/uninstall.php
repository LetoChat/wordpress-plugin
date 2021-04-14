<?php

// If uninstall not called from WordPress, then exit.
if (!defined( 'WP_UNINSTALL_PLUGIN')) {
	exit;
}

foreach (PLUGIN_LETO_CHAT_SETTINGS_OPTIONS as $settingOption) {
    delete_option($settingOption);
}
