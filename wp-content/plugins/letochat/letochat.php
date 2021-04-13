<?php

/**
 * Plugin Name:       LetoChat - Chat with your visitors
 * Plugin URI:        https://www.letochat.com/
 * Description:       -
 * Version:           1.0.0
 * Requires at least: 5.7
 * Requires PHP:      5.6
 * Author:            Sabin Mehedin
 * Author URI:        https://sgmedia.ro/
 * License:           GPL v3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       letochat
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/vendor/autoload.php';

use LetoChat\Includes\LetoChatActivator;
use LetoChat\Includes\LetoChatDeactivator;
use LetoChat\Includes\LetoChat;

define('LETOCHAT_VERSION', '1.0.0');
define('PLUGIN_LETO_CHAT_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_LETO_CHAT_URL', plugin_dir_url(__FILE__));
define('PLUGIN_LETO_CHAT_SLUG', plugin_basename(__FILE__));

function create_leto_chat_constants()
{
    define('PLUGIN_LETO_CHAT_ENVIRONMENT_TYPE', wp_get_environment_type());
}
add_action('plugins_loaded', 'create_leto_chat_constants');

function leto_chat_activate_plugin()
{
    LetoChatActivator::activate();
}

function leto_chat_deactivate_plugin()
{
    LetoChatDeactivator::deactivate();
}

register_activation_hook(__FILE__, 'leto_chat_activate_plugin');
register_deactivation_hook(__FILE__, 'leto_chat_deactivate_plugin');

function leto_chat_run_plugin()
{
    (new LetoChat())->run();
}
leto_chat_run_plugin();
