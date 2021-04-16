<?php

namespace LetoChat\PublicView\Business\Model\Api;

use LetoChat\Config\AbstractConfigInterface;
use LetoChat\Includes\BaseApi;
use LetoChat\Includes\LetoChatHelper;
use LetoChat\PublicView\Persistence\LetoChatRepositoryInterface;
use \WP_REST_Server;
use \WP_Error;
use \WP_HTTP_Response;
use \WC_Session_Handler;

class UserCart extends BaseApi implements UserCartInterface
{
    use LetoChatHelper;

    /**
     * @var AbstractConfigInterface
     */
    private $config;

    /**
     * @var LetoChatRepositoryInterface
     */
    private $repository;

    /**
     * UserCart constructor.
     * @param AbstractConfigInterface $config
     * @param LetoChatRepositoryInterface $repository
     */
    public function __construct($config, $repository)
    {
        $this->config = $config;
        $this->repository = $repository;
        $this->namespace = self::API_NAMESPACE;
        $this->rest_base = self::API_USER_CART_ROUTE;
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

    /**
     * @param $request
     * @return bool|WP_Error
     */
    public function getUserCartPermissionsCheck($request)
    {
        return $this->permissionCheck($request, $this->config);
    }

    /**
     * @param $request
     * @return WP_Error|WP_HTTP_Response
     */
    public function getUserCart($request)
    {
        if ($request->get_param('user_id') === null) {
            return new WP_Error(
                400,
                __('Bad Request.', 'letochat')
            );
        }

        $userId = wp_kses($request->get_param('user_id'), []);
        $woocommerceSessions = $this->repository->getUsersIdFromWoocommerceSessions();

        if (in_array($userId, $woocommerceSessions) === false) {
            return new WP_Error(
                404,
                __('Invalid user ID.', 'letochat'),
            );
        }

        $userCartData = $this->getCartItemsByUserId($userId);

        if ($userCartData === false) {
            return new WP_Error(
                404,
                __('No cart founds.', 'letochat')
            );
        }

        return new WP_HTTP_Response([
            'data' => $userCartData,
        ], 200);
    }

    /**
     * @param $request
     * @return bool|WP_Error
     */
    public function getUsersCartPermissionsCheck($request)
    {
        return $this->permissionCheck($request, $this->config);
    }

    /**
     * @param $request
     * @return WP_Error|WP_HTTP_Response
     */
    public function getUsersCart($request)
    {
        if ($request->get_param('ids') === null) {
            return new WP_Error(
                400,
                __('Bad Request.', 'letochat')
            );
        }

        $usersIds = wp_kses($request->get_param('ids'), []);

        $usersIdsArray = explode(',', $usersIds);

        $usersCarts = [];

        if (is_array($usersIdsArray) === true) {
            $woocommerceSessions = $this->repository->getUsersIdFromWoocommerceSessions();

            foreach ($usersIdsArray as $userId) {
                if (in_array($userId, $woocommerceSessions) === false) {
                    continue;
                }

                $usersCarts[] = $this->getUserCartDataByUserId($userId);
            }
        } else {
            $usersCarts = $this->getUserCartDataByUserId($usersIds);
        }

        return new WP_HTTP_Response([
            'data' => $usersCarts
        ], 200);
    }

    /**
     * @param string $userId
     * @return array
     */
    private function getCartItemsByUserId($userId)
    {
        $cartItems = $this->cartItemsByUserId($userId);

        $items = [];

        if ($cartItems === false) {
            return $items;
        }

        foreach ($cartItems as $cartItemKey => $cartItem) {
            $item = new \stdClass();

            $item->name = get_the_title($cartItem['product_id']);
            $item->quantity = $cartItem['quantity'];
            $item->total = $cartItem['line_total'];

            $productImageUrl = wp_get_attachment_image_src(get_post_thumbnail_id($cartItem['product_id']), 'single-post-thumbnail');

            if ($productImageUrl !== false) {
                $item->image = $productImageUrl[0];
            }
            $item->link = get_permalink($cartItem['product_id']);

            $items[] = $item;
        }

        return $items;
    }

    /**
     * @param string $userId
     * @return \stdClass
     */
    private function getUserCartDataByUserId($userId)
    {
        $cartItems = $this->cartItemsByUserId($userId);

        if ($cartItems === false) {
            return new \stdClass();
        }

        $total = 0;
        $cart = new \stdClass();
        $cart->user_id = $userId;
        $cart->currency = get_woocommerce_currency();

        foreach ($cartItems as $cartItemKey => $cartItem) {
            $total = $total + $cartItem['line_total'];
        }

        $cart->total = $total;

        return $cart;
    }

    /**
     * @param string $userId
     * @return false|mixed|string
     */
    private function cartItemsByUserId($userId)
    {
        // Get an instance of the WC_Session_Handler Object
        $sessionHandler = new WC_Session_Handler();

        // Get the user session from its user ID
        $session = $sessionHandler->get_session($userId);

        if ($session === false) {
            return false;
        }

        // Get cart items array
        return maybe_unserialize($session['cart']);
    }
}