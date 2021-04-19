<?php

namespace LetoChat\PublicView\Business\Model;

use LetoChat\Config\AbstractConfigInterface;
use LetoChat\Includes\LetoChatHelper;
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
     * Widget constructor.
     * @param AbstractConfigInterface $config
     * @param GenericLetoChatWidget $chat
     */
    public function __construct($config, $chat)
    {
        $this->config = $config;
        $this->chat = $chat;
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

            echo $this->chat->build();
        } catch (Exception $e) {
            echo '';
        }
    }

    public function addToCartEvent($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
    {
        $infoValues = [];

        if ($this->getUserId() !== 0) {
            $infoValues['id'] = $this->getUserId();
        }

        $infoValues = $this->getInfoValues($infoValues);

        /**
         * @var WC_Product $product
         */
        $product = wc_get_product($product_id);
        $productName = $product->get_name();
        $productPrice = $product->get_price();
        $productImage = $product->get_image();

        $productData = [
            'id' => $product_id,
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

        add_action('letochat-script', function() use ($infoValues, $productData) {
            try {
                $this->chat->infoValues($infoValues);

                $this->chat->event('cart-add', $productData);

                echo $this->chat->build();
            } catch (Exception $e) {
                echo '';
            }
        });
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
}