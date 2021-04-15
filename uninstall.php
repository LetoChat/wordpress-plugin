<?php

// If uninstall not called from WordPress, then exit.
if (!defined( 'WP_UNINSTALL_PLUGIN')) {
	exit;
}

$settingsOptions = [
    'is_connected' => 'letochat_is_connected',
    'channel_id' => 'letochat_channel_id',
    'channel_secret' => 'letochat_channel_secret',
    'auth_secret' => 'letochat_auth_secret',
    'enable_widget' => 'letochat_enable_widget',
    'visible_for_admins' => 'letochat_visible_for_admins',
];

foreach ($settingsOptions as $settingOption) {
    delete_option($settingOption);
}
