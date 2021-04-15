<?php

namespace LetoChat\Config;

use Defuse\Crypto\Key;

class AbstractConfig implements AbstractConfigInterface
{
    public function getLetoChatEncryptKey()
    {
        $key = get_option(PLUGIN_LETO_CHAT_SETTINGS_OPTIONS['enc_key'], false);

        return Key::loadFromAsciiSafeString($key);
    }

    public function getSettingsOptions()
    {
        return PLUGIN_LETO_CHAT_SETTINGS_OPTIONS;
    }
}
