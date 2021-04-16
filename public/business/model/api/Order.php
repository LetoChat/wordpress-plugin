<?php

namespace LetoChat\PublicView\Business\Model\Api;

use LetoChat\Config\AbstractConfigInterface;
use LetoChat\Includes\BaseApi;
use LetoChat\Includes\LetoChatHelper;
use \WP_REST_Server;
use \WP_Error;
use \WP_HTTP_Response;

class Order extends BaseApi implements OrderInterface
{
    use LetoChatHelper;

    /**
     * @var string
     */
    protected $post_type;

    /**
     * @var AbstractConfigInterface
     */
    private $config;

    /**
     * Order constructor.
     * @param AbstractConfigInterface $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->post_type = 'shop_order';
        $this->namespace = self::API_NAMESPACE;
        $this->rest_base = self::API_ORDER_ROUTE;
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

    /**
     * @param $request
     * @return bool|WP_Error
     */
    public function getOrderPermissionsCheck($request)
    {
        return $this->permissionCheck($request, $this->config);
    }

    /**
     * @param $request
     * @return WP_Error|WP_HTTP_Response
     */
    public function getOrder($request)
    {
        if ($request->get_param('id') === null) {
            return new WP_Error(
                400,
                __('Bad Request.', 'letochat')
            );
        }

        $orderId = wp_kses($request->get_param('id'), []);
        $order = get_post($orderId);

        if (empty($orderId) || empty($order->ID) || $order->post_type !== $this->post_type) {
            return new WP_Error(
                404,
                __('Invalid order ID.', 'letochat')
            );
        }

        return new WP_HTTP_Response([
            'data' => $this->getOrderPrepared($order),
        ], 200);
    }

    /**
     * @param $request
     * @return bool|WP_Error
     */
    public function getOrdersPermissionsCheck($request)
    {
        return $this->permissionCheck($request, $this->config);
    }

    /**
     * @param $request
     * @return WP_Error|WP_HTTP_Response
     */
    public function getOrders($request)
    {
        if ($request->get_param('user_id') === null) {
            return new WP_Error(
                400,
                __('Bad Request.', 'letochat')
            );
        }

        $userId = wp_kses($request->get_param('user_id'), []);

        if (empty($userId) || get_user_by('ID', $userId) === false) {
            return new WP_Error(
                404,
                __('Invalid user ID.', 'letochat')
            );
        }

        $orders = [];
        $ordersId = wc_get_orders(array(
            'customer_id' => $userId,
            'return' => 'ids',
        ));

        if (empty($ordersId)) {
            return new WP_Error(
                404,
                __('No orders found.', 'letochat')
            );
        }

        $userOrders = get_posts([
            'post_type' => $this->post_type,
            'include' => $ordersId,
            'post_status' => 'any',
        ]);

        foreach ($userOrders as $order) {
            $orders[] = $this->getOrderPreparedForBulk($order);
        }

        return new WP_HTTP_Response([
            'data' => $orders,
        ], 200);
    }

    /**
     * @param \WP_Post $order
     * @return \stdClass
     */
    private function getOrderPrepared($order)
    {
        $orderStatuses = wc_get_order_statuses();
        $order = wc_get_order($order);

        $data = new \stdClass();

        $data->status = $orderStatuses['wc-' . $order->get_status()];
        $data->currency = $order->get_currency();
        $data->date_created = wc_rest_prepare_date_response($order->get_date_created());
        $data->date_paid = wc_rest_prepare_date_response($order->get_date_paid());
        $data->total = wc_format_decimal($order->get_total(), 2);

        $billing = $order->get_address('billing');
        $this->formatBillingAddress($billing, $data);

        $shipping = $order->get_address('shipping');
        $this->formatShippingAddress($shipping, $data);

        $data->payment_method = $order->get_payment_method_title();
        $data->additional_information = $order->get_customer_note();

        $data->items = [];

        foreach ($order->get_items() as $itemId => $item) {
            $product = $item->get_product();
            $itemFormatted = new \stdClass();

            if (is_object($product)) {
                $itemFormatted->sku = $product->get_sku();
            }

            $itemFormatted->name = $item['name'];
            $itemFormatted->quantity = wc_stock_amount($item['qty']);
            $itemFormatted->price = wc_format_decimal($order->get_item_total($item, false, false), 2);
            $itemFormatted->total = wc_format_decimal($order->get_total(), 2);

            $productImageUrl = wp_get_attachment_image_src(get_post_thumbnail_id($item->get_product_id()), 'single-post-thumbnail');

            if ($productImageUrl !== false) {
                $itemFormatted->image = $productImageUrl[0];
            }

            $itemFormatted->link = get_permalink($item->get_product_id());

            $data->items[] = $itemFormatted;
        }

        return $data;
    }

    /**
     * @param \WP_Post $order
     * @return \stdClass
     */
    private function getOrderPreparedForBulk($order)
    {
        $orderStatuses = wc_get_order_statuses();
        $order = wc_get_order($order);

        $data = new \stdClass();

        $data->id = $order->get_id();
        $data->status = $orderStatuses['wc-' . $order->get_status()];
        $data->currency = $order->get_currency();
        $data->date_created = wc_rest_prepare_date_response($order->get_date_created());
        $data->total = wc_format_decimal($order->get_total(), 2);

        return $data;
    }

    /**
     * @param array $billing
     * @param \stdClass $data
     */
    private function formatBillingAddress($billing, $data)
    {
        if (!empty($billing['first_name'])) {
            $data->billing['name'] = $billing['first_name'];
        }

        if (!empty($billing['last_name'])) {
            $data->billing['name'] = $data->billing['name'] . ' ' . $billing['last_name'];
        }

        if (!empty($billing['company'])) {
            $data->billing['company'] = $billing['company'];
        }

        $billingAddress = '';

        $billingAddressKeys = [
            'address_1',
            'address_2',
            'city',
            'postcode',
            'country',
        ];

        foreach ($billingAddressKeys as $key => $billingAddressKey) {
            if (!empty($billing[$billingAddressKey])) {
                if ($key === count($billingAddressKeys) - 1) {
                    $billingAddress = $billingAddress . $billing[$billingAddressKey];
                } else {
                    $billingAddress = $billingAddress . $billing[$billingAddressKey] . ', ';
                }
            }
        }

        if (!empty($billingAddress)) {
            $data->billing['address'] = $billingAddress;
        }

        if (!empty($billing['email'])) {
            $data->billing['email'] = $billing['email'];
        }

        if (!empty($billing['phone'])) {
            $data->billing['phone'] = $billing['phone'];
        }
    }

    /**
     * @param array $shipping
     * @param \stdClass $data
     */
    private function formatShippingAddress($shipping, $data)
    {
        if (!empty($shipping['first_name'])) {
            $data->shipping['name'] = $shipping['first_name'];
        }

        if (!empty($shipping['last_name'])) {
            $data->shipping['name'] = $data->shipping['name'] . ' ' . $shipping['last_name'];
        }
        if (!empty($shipping['company'])) {
            $data->shipping['company'] = $shipping['company'];
        }

        $shippingAddress = '';

        $shippingAddressKeys = [
            'address_1',
            'address_2',
            'city',
            'postcode',
            'country',
        ];

        foreach ($shippingAddressKeys as $key => $shippingAddressKey) {
            if (!empty($shipping[$shippingAddressKey])) {
                if ($key === count($shippingAddressKeys) - 1) {
                    $shippingAddress = $shippingAddress . $shipping[$shippingAddressKey];
                } else {
                    $shippingAddress = $shippingAddress . $shipping[$shippingAddressKey] . ', ';
                }
            }
        }

        if (!empty($shippingAddress)) {
            $data->shipping['address'] = $shippingAddress;
        }

        if (!empty($shipping['email'])) {
            $data->shipping['email'] = $shipping['email'];
        }

        if (!empty($shipping['phone'])) {
            $data->shipping['phone'] = $shipping['phone'];
        }
    }
}