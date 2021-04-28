<?php

namespace LetoChat\PublicView;

use LetoChat\AdminView\Business\LetoChatFacade as LetoChatAdminViewFacade;
use LetoChat\PublicView\Business\LetoChatFacade as LetoChatPublicViewFacade;

class LetoChatPublic
{
	private $pluginName;

	private $version;

	private $adminViewFacade;

	private $publicViewFacade;

	public function __construct($pluginName, $version)
	{
		$this->pluginName = $pluginName;
		$this->version = $version;
		$this->adminViewFacade = LetoChatAdminViewFacade::getInstance();
		$this->publicViewFacade = LetoChatPublicViewFacade::getInstance();
	}

	public function enqueue_styles()
	{
//		wp_enqueue_style($this->pluginName, '', [], $this->version, 'all');
	}

	public function enqueue_scripts()
	{
		wp_register_script($this->pluginName, PLUGIN_LETO_CHAT_URL . 'public/js/letochat.js', ['jquery'], $this->version, true);
		wp_localize_script($this->pluginName, 'ajax_letochat_public_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('ajax_letochat_public'),
        ]);
		wp_enqueue_script($this->pluginName);
	}

    public function addScript()
    {
        $this->publicViewFacade->addScript();
    }

    public function addToCartEvent($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
    {
        remove_action('wp_footer', [$this, 'addScript']);

        $this->publicViewFacade->addToCartEvent($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data);
    }

    public function registerApiRoutes()
    {
        $this->publicViewFacade->registerApiRoutes();
    }

    public function sessionStoreForProductAdded($product_id)
    {
        $this->publicViewFacade->sessionStoreForProductAdded($product_id);
    }

    public function updateChatTokenAjaxBehavior()
    {
        $this->publicViewFacade->updateChatTokenAjaxBehavior();
    }
}
