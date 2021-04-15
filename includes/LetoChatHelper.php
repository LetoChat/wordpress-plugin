<?php

namespace LetoChat\Includes;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;

trait LetoChatHelper
{
    public function renderView($path, $view)
    {
        ob_start();
        include($path);
        $template = ob_get_contents();
        ob_end_clean();

        return $template;
    }

    public function myPrint($var)
    {
        echo '<pre>' . print_r($var, true) . '</pre>';
    }

    public function update_option($option, $value, $encryptionKey, $autoload = null)
    {
        return update_option($option, Crypto::encrypt($value, $encryptionKey), $autoload);
    }

    public function add_option($option, $value, $encryptionKey, $autoload = 'yes')
    {
        add_option($option, Crypto::encrypt($value, $encryptionKey), '', $autoload);
    }

    public function get_option($option, $encryptionKey, $default = false)
    {
        $encrypted =  get_option($option, $default);

        if ($encrypted === $default) {
            return $default;
        } else {
            try {
                $value = Crypto::decrypt($encrypted, $encryptionKey);

                return $value;
            } catch (WrongKeyOrModifiedCiphertextException $e) {
                return '';
            }
        }
    }

    public function woocommerceIsActivated()
    {
        $activated = false;

        if (!function_exists( 'is_plugin_active_for_network')) {
            require_once(ABSPATH . '/wp-admin/includes/plugin.php');
        }

        // multisite
        if (is_multisite()) {
            // this plugin is network activated - Woo must be network activated
            if (is_plugin_active_for_network(plugin_basename(__FILE__))) {
                $activated = is_plugin_active_for_network('woocommerce/woocommerce.php') ? true : false;
                // this plugin is locally activated - Woo can be network or locally activated
            } else {
                $activated = is_plugin_active('woocommerce/woocommerce.php') ? true : false;
            }
            // this plugin runs on a single site
        } else {
            $activated = is_plugin_active('woocommerce/woocommerce.php') ? true : false;
        }

        return $activated;
    }
}