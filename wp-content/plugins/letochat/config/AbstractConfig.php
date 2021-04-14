<?php

namespace LetoChat\Config;

use Defuse\Crypto\Key;

class AbstractConfig implements AbstractConfigInterface
{
    public function getLetoChatEncryptKey()
    {
        return Key::loadFromAsciiSafeString(PLUGIN_LETO_CHAT_KEY_STRING);
    }

    public function getSettingsOptions()
    {
        return PLUGIN_LETO_CHAT_SETTINGS_OPTIONS;
    }
}
