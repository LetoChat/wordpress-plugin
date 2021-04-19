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
//		wp_enqueue_script($this->pluginName, '', ['jquery'], $this->version, false );
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

    public function addLetoChatHookInFooter()
    {
        do_action('letochat-script');
    }
}
