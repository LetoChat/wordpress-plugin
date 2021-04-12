<?php

/**
 * Plugin Name:       Letochat
 * Plugin URI:        -
 * Description:       -
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Sabin Mehedin
 * Author URI:        -
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       letochat
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/vendor/autoload.php';

use Letochat\Includes\LetochatActivator;
use Letochat\Includes\LetochatDeactivator;
use Letochat\Includes\Letochat;

define('LETOCHAT_VERSION', '1.0.0');
define('PLUGIN_LETOCHAT_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_LETOCHAT_URL', plugin_dir_url(__FILE__));
define('PLUGIN_LETOCHAT_SLUG', plugin_basename(__FILE__));

function create_letochat_plugin_environment_type()
{
    define('PLUGIN_LETOCHAT_ENVIRONMENT_TYPE', wp_get_environment_type());
}
add_action('init', 'create_letochat_plugin_environment_type');

function letochat_activate_plugin()
{
    LetochatActivator::activate();
}

function letochat_deactivate_plugin()
{
    LetochatDeactivator::deactivate();
}

register_activation_hook(__FILE__, 'letochat_activate_plugin');
register_deactivation_hook(__FILE__, 'letochat_deactivate_plugin');

function letochat_run_plugin()
{
    (new Letochat())->run();
}
letochat_run_plugin();
