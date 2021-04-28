<?php

namespace LetoChat\Includes;

use LetoChat\Config\AbstractConfigInterface;
use \WP_REST_Controller;
use \WP_Error;

abstract class BaseApi extends WP_REST_Controller
{
    use LetoChatHelper;

    public const API_NAMESPACE = 'letochat/v1';

    public const API_ORDER_ROUTE = 'order';

    public const API_USER_CART_ROUTE = 'user-cart';

    /**
     * @param $request
     * @param AbstractConfigInterface $config
     * @return bool|WP_Error
     */
    protected function permissionCheck($request, $config)
    {
        if ($request->get_param('auth_secret') === null) {
            return new WP_Error(
                400,
                __('Bad Request.', 'letochat')
            );
        }

        $appAuthSecret = $request->get_param('auth_secret');

        $settingsOptions = $config->getSettingsOptions();

        $authSecret = $this->get_option($settingsOptions['auth_secret'], $config->getLetoChatEncryptKey());

        if ($appAuthSecret !== $authSecret) {
            return new WP_Error(
                401,
                __('Unauthorized.', 'letochat')
            );
        }

        return true;
    }
}