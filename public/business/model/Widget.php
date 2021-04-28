<?php

namespace LetoChat\PublicView\Business\Model;

use LetoChat\Config\AbstractConfigInterface;
use LetoChat\Includes\LetoChatHelper;
use LetoChat\Includes\PluginResponse;
use \LetoChat\Widget as GenericLetoChatWidget;
use \Exception;
use \WC_Session_Handler;
use \WC_Customer;
use \WC_Product;

class Widget implements WidgetInterface
{
    use LetoChatHelper;

    /**
     * @var AbstractConfigInterface
     */
    private $config;

    /**
     * @var GenericLetoChatWidget
     */
    private $chat;

    /**
     * @var PluginResponse
     */
    private $pluginResponse;

    /**
     * Widget constructor.
     * @param AbstractConfigInterface $config
     * @param GenericLetoChatWidget $chat
     * @param PluginResponse $pluginResponse
     */
    public function __construct($config, $chat, $pluginResponse)
    {
        $this->config = $config;
        $this->chat = $chat;
        $this->pluginResponse = $pluginResponse;
    }

    public function addScript()
    {
        $settingsOptions = $this->config->getSettingsOptions();

        $isEnabled = get_option($settingsOptions['enable_widget']);

        if ($isEnabled === 'off') {
            return;
        }

        $isVisibleForAdmins = get_option($settingsOptions['visible_for_admins']);

        if ($this->hideForAdmins($isVisibleForAdmins) === true) {
            return;
        }

        $infoValues = [];

        if ($this->getUserId() !== 0) {
            $infoValues['id'] = $this->getUserId();
        }

        if ($this->woocommerceIsActivated() === true) {
            $infoValues = $this->getInfoValues($infoValues);
        }

        try {
            $this->chat->infoValues($infoValues);

            echo '<div id="letochat-script">' . $this->chat->build() . '</div>';
        } catch (Exception $e) {
            echo '';
        }
    }

    /**
     * @param $cart_item_key
     * @param $product_id
     * @param $quantity
     * @param $variation_id
     * @param $variation
     * @param $cart_item_data
     */
    public function addToCartEvent($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
    {
        $infoValues = [];

        if ($this->getUserId() !== 0) {
            $infoValues['id'] = $this->getUserId();
        }

        $infoValues = $this->getInfoValues($infoValues);

        $productData = $this->getProductData($product_id, $quantity);

        add_action('wp_footer', function() use ($infoValues, $productData) {
            $settingsOptions = $this->config->getSettingsOptions();

            $isEnabled = get_option($settingsOptions['enable_widget']);

            if ($isEnabled === 'off') {
                return;
            }

            $isVisibleForAdmins = get_option($settingsOptions['visible_for_admins']);

            if ($this->hideForAdmins($isVisibleForAdmins) === true) {
                return;
            }

            try {
                $this->chat->infoValues($infoValues);

                $this->chat->event('cart-add', $productData);

                $token = $this->chat->token();

                echo sprintf('<div id="letochat-script"><script>LetoChat("update_token", "%s");</script></div>', $token);
            } catch (Exception $e) {
                echo '';
            }
        });
    }

    /**
     * @param $product_id
     */
    public function sessionStoreForProductAdded($product_id)
    {
        WC()->session->set('letochat_last_product_id', $product_id);
    }

    public function updateChatTokenAjaxBehavior()
    {
        wp_verify_nonce('ajax_letochat_public', $_POST['security']);

        $infoValues = [];

        if ($this->getUserId() !== 0) {
            $infoValues['id'] = $this->getUserId();
        }

        $infoValues = $this->getInfoValues($infoValues);

        $product_id = WC()->session->get('letochat_last_product_id');

        if (empty($product_id)) {
            $this->pluginResponse->isSuccess = false;

            echo json_encode($this->pluginResponse);

            wp_die();
        }

        WC()->session->__unset('letochat_last_product_id');

        $productData = $this->getProductData($product_id, 1);

        $settingsOptions = $this->config->getSettingsOptions();

        $isEnabled = get_option($settingsOptions['enable_widget']);

        if ($isEnabled === 'off') {
            $this->pluginResponse->isSuccess = false;

            echo json_encode($this->pluginResponse);

            wp_die();
        }

        $isVisibleForAdmins = get_option($settingsOptions['visible_for_admins']);

        if ($this->hideForAdmins($isVisibleForAdmins) === true) {
            $this->pluginResponse->isSuccess = false;

            echo json_encode($this->pluginResponse);

            wp_die();
        }

        try {
            $this->chat->infoValues($infoValues);

            $this->chat->event('cart-add', $productData);

            $token = $this->chat->token();

            $chat = sprintf('<script>LetoChat("update_token", "%s");</script>', $token);

            $this->pluginResponse->isSuccess = true;
            $this->pluginResponse->chat = $chat;

            echo json_encode($this->pluginResponse);

            wp_die();
        } catch (Exception $e) {
            $this->pluginResponse->isSuccess = false;

            echo json_encode($this->pluginResponse);

            wp_die();
        }
    }

    /**
     * @param $isVisible
     * @return bool
     */
    private function hideForAdmins($isVisible)
    {
        if (is_user_logged_in() === true) {
            $user = wp_get_current_user();

            $roles = $user->roles;

            if (in_array('administrator', $roles) === true && $isVisible === 'off') {
                return true;
            }
        }

        return false;
    }

    /**
     * @return int|mixed
     */
    private function getUserId()
    {
        if (is_user_logged_in() === true) {
            return get_current_user_id();
        }

        $wcSessionHandler = new WC_Session_Handler();
        $guestSession = $wcSessionHandler->get_session_cookie();

        if ($guestSession === false) {
            return 0;
        }

        return $guestSession[0];
    }

    private function getInfoValues($infoValues)
    {
        $infoValues['logged'] = false;

        if (is_user_logged_in() === true) {
            $infoValues['logged'] = true;

            $currentUserId = get_current_user_id();
            $currentUserData = new WC_Customer($currentUserId);

            $fullName = '';

            if (!empty($currentUserData->get_first_name()) && !empty($currentUserData->get_last_name())) {
                $fullName = sprintf('%s %s', $currentUserData->get_first_name(), $currentUserData->get_last_name());
            } elseif (!empty($currentUserData->get_first_name())) {
                $fullName = $currentUserData->get_first_name();
            } elseif (!empty($currentUserData->get_last_name())) {
                $fullName = $currentUserData->get_last_name();
            }

            if (!empty($fullName)) {
                $infoValues['name'] = $fullName;
            }

            if (!empty($currentUserData->get_avatar_url())) {
                $infoValues['avatar'] = $currentUserData->get_avatar_url();
            }

            if (!empty($currentUserData->get_billing_email())) {
                $infoValues['email'] = $currentUserData->get_billing_email();
            }

            if (!empty($currentUserData->get_billing_phone())) {
                $infoValues['phone'] = $currentUserData->get_billing_phone();
            }

            if (!empty($currentUserData->get_billing_company())) {
                $infoValues['company_name'] = $currentUserData->get_billing_company();
            }
        }

        return $infoValues;
    }

    /**
     * @param int $product_id
     * @param int $quantity
     * @return array
     */
    private function getProductData($product_id, $quantity)
    {
        /**
         * @var WC_Product $product
         */
        $product = wc_get_product($product_id);
        $productName = $product->get_name();
        $productPrice = $product->get_price();
        $productImageId = $product->get_image_id();
        $productImage = wp_get_attachment_url($productImageId);

        $productData = [
            'quantity' => $quantity,
            'link' => $product->get_permalink(),
            'currency' => get_woocommerce_currency(),
        ];

        if (!empty($productName)) {
            $productData['name'] = $productName;
        }

        if (!empty($productImage)) {
            $productData['image'] = $productImage;
        }

        if (!empty($productPrice)) {
            $productData['price'] = $productPrice;
        }

        return $productData;
    }
}