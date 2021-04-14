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
}