<?php

namespace LetoChat\PublicView\Business\Model\Api;

use LetoChat\Includes\BaseApi;
use LetoChat\Includes\PluginResponse;
use \WP_REST_Server;
use \WP_Error;
use \WP_HTTP_Response;
use \WC_REST_Orders_V1_Controller;

class Order extends WC_REST_Orders_V1_Controller implements OrderInterface
{
    private $pluginResponse;

    protected $post_type;

    public function __construct(PluginResponse $pluginResponse)
    {
        parent::__construct();

        $this->namespace = BaseApi::getApiNamespace();
        $this->rest_base = BaseApi::getApiOrderRoute();
        $this->pluginResponse = $pluginResponse;
        $this->post_type = 'shop_order';
    }

    public function registerRoutes()
    {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => [$this, 'getOrder'],
                    'permission_callback' => [$this, 'getOrderPermissionsCheck'],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/all',
            [
                [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => [$this, 'getOrders'],
                    'permission_callback' => [$this, 'getOrdersPermissionsCheck'],
                ],
            ]
        );
    }

    public function getOrderPermissionsCheck($request)
    {
        // IN PROGRESS
        return true;
    }

    public function getOrder($request)
    {
        if ($request->get_param('id') === null) {
            return new WP_Error(
                'letochat_rest_bad_request',
                __('Bad Request.', 'leto-chat-affiliate'),
                [
                    'status' => 400,
                ]
            );
        }

        $orderId = wp_kses($request->get_param('id'), []);
        $order = get_post($orderId);
        $request['dp'] = $orderId;

        if (empty($orderId) || empty($order->ID) || $order->post_type !== $this->post_type) {
            return new WP_Error(
                'letochat_rest_invalid_order_id',
                __('Invalid order ID.', 'letochat'),
                [
                    'status' => 404,
                ]
            );
        }

        $orderFormatted = $this->prepare_item_for_response($order, $request);

        return new WP_HTTP_Response([
            'code' => 'letochat_rest_user_order',
            'data' => [
                'order' => $orderFormatted,
                'status' => 200,
            ]
        ], 200);
    }

    public function getOrdersPermissionsCheck($request)
    {
        // IN PROGRESS
        return true;
    }

    public function getOrders($request)
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
        $orders = [];
        $ordersId = wc_get_orders(array(
            'customer_id' => $userId,
            'return' => 'ids',
        ));

        if (empty($ordersId)) {
            return new WP_Error(
                'letochat_rest_no_orders',
                __('No orders found.', 'letochat'),
                [
                    'status' => 404,
                ]
            );
        }

        $userOrders = get_posts([
            'post_type' => 'shop_order',
            'include' => $ordersId,
            'post_status' => 'any',
        ]);

        foreach ($userOrders as $order) {
            $data = $this->prepare_item_for_response($order, $request);

            $orders[] = $this->prepare_response_for_collection($data);
        }

        return new WP_HTTP_Response([
            'code' => 'letochat_rest_user_orders',
            'data' => [
                'orders' => $orders,
                'status' => 200,
            ]
        ], 200);
    }
}