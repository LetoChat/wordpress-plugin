<?php

namespace LetoChat\PublicView\Business\Model\Api;

use LetoChat\Includes\BaseApi;
use \WC_REST_Posts_Controller;
use \WP_REST_Server;
use \WP_Error;
use \WP_HTTP_Response;
use \WC_Session_Handler;

class UserCart extends WC_REST_Posts_Controller implements UserCartInterface
{
    public function __construct()
    {
        $this->namespace = BaseApi::getApiNamespace();
        $this->rest_base = BaseApi::getApiUserCartRoute();
    }

    public function registerRoutes()
    {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => [$this, 'getUserCart'],
                    'permission_callback' => [$this, 'getUserCartPermissionsCheck'],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/all',
            [
                [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => [$this, 'getUsersCart'],
                    'permission_callback' => [$this, 'getUsersCartPermissionsCheck'],
                ],
            ]
        );
    }

    public function getUserCartPermissionsCheck($request)
    {
        // IN PROGRESS
        return true;
    }

    public function getUserCart($request)
    {
        if ($request->get_param('user_id') === null) {
            return new WP_Error(
                'letochat_rest_bad_request',
                __('Bad Request.', 'leto-chat-affiliate'),
                [
                    'status' => 400,
                ]
            );
        }

        $userId = wp_kses($request->get_param('user_id'), []);

        if (get_user_by('ID', $userId) === false) {
            return new WP_Error(
                'letochat_rest_invalid_user_id',
                __('Invalid user ID.', 'letochat'),
                [
                    'status' => 404,
                ]
            );
        }

        $userCartData = $this->userCartData($userId);

        if ($userCartData === false) {
            return new WP_Error(
                'letochat_rest_no_cart',
                __('No cart founds.', 'letochat'),
                [
                    'status' => 404,
                ]
            );
        }

        return new WP_HTTP_Response([
            'code' => 'letochat_rest_user_cart',
            'data' => [
                'user_id' => $userId,
                'carts' => $userCartData,
                'status' => 200,
            ]
        ], 200);
    }

    public function getUsersCartPermissionsCheck($request)
    {
        // IN PROGRESS
        return true;
    }

    public function getUsersCart($request)
    {
        if ($request->get_param('users_ids') === null) {
            return new WP_Error(
                'letochat_rest_bad_request',
                __('Bad Request.', 'leto-chat-affiliate'),
                [
                    'status' => 400,
                ]
            );
        }

        $usersIds = wp_kses($request->get_param('users_ids'), []);

        $usersIdsArray = explode(',', $usersIds);

        $usersCarts = [];

        if (is_array($usersIdsArray) === true) {
            foreach ($usersIdsArray as $userId) {
                if (get_user_by('ID', $userId) === false) {
                    continue;
                }

                $usersCarts[] = [
                    'user_id' => $userId,
                    'cart' => $this->userCartData($userId),
                ];
            }
        } else {
            $usersCarts = $this->userCartData($usersIds);
        }

        return new WP_HTTP_Response([
            'code' => 'letochat_rest_user_cart',
            'data' => [
                'carts' => $usersCarts,
                'status' => 200,
            ]
        ], 200);
    }

    private function userCartData($userId)
    {
        // Get an instance of the WC_Session_Handler Object
        $sessionHandler = new WC_Session_Handler();

        // Get the user session from its user ID
        $session = $sessionHandler->get_session($userId);

        if ($session === false) {
            return false;
        }

        // Get cart items array
        $cartItems = maybe_unserialize($session['cart']);

        $items = [];

        foreach ($cartItems as $cartItemKey => $cartItem) {
            $items[] = [
              'product_id' => $cartItem['product_id'],
              'variation_id' => $cartItem['variation_id'],
              'quantity' => $cartItem['quantity'],
              'attributes' => $cartItem['variation'],
              'item_taxes' => $cartItem['line_tax_data'],
              'subtotal_tax' => $cartItem['line_subtotal_tax'],
              'total_tax' => $cartItem['line_tax'],
              'subtotal' => $cartItem['line_subtotal'],
              'total' => $cartItem['line_total'],
            ];
        }

        return $items;
    }
}