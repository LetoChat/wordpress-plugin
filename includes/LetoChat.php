<?php

namespace LetoChat\Includes;

use LetoChat\AdminView\LetoChatAdmin;
use LetoChat\PublicView\LetoChatPublic;

class LetoChat
{
	protected $loader;

	protected $i18n;

	protected $adminView;

	protected $publicView;

	protected $pluginName;

	protected $version;

	public function __construct()
	{
		if (defined('LETO_CHAT_VERSION')) {
			$this->version = LETO_CHAT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->pluginName = 'letochat';

		$this->loadDependencies();
		$this->setLocale();
		$this->defineAdminHooks();
		$this->definePublicHooks();
	}

	private function loadDependencies()
	{
        $this->i18n = new LetoChatI18n();

        $this->adminView = new LetoChatAdmin($this->pluginName, $this->version);

        $this->publicView = new LetoChatPublic($this->pluginName, $this->version);

		$this->loader = new LetoChatLoader();

	}

	private function setLocale()
	{
		$plugin_i18n = new LetoChatI18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	private function defineAdminHooks()
	{
		$this->loader->add_action('admin_enqueue_scripts', $this->adminView, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $this->adminView, 'enqueue_scripts');
		$this->loader->add_action('admin_menu', $this->adminView, 'adminMenu');
		$this->loader->add_action('wp_ajax_letochat_check_data', $this->adminView, 'connectToLetoChat');
		$this->loader->add_action('wp_ajax_letochat_switcher', $this->adminView, 'switcherAjaxCall');
	}

	private function definePublicHooks()
	{
		// Actions
		$this->loader->add_action('wp_enqueue_scripts', $this->publicView, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $this->publicView, 'enqueue_scripts');
		$this->loader->add_action('wp_footer', $this->publicView, 'addScript');
		$this->loader->add_action('wp_footer', $this->publicView, 'addLetoChatHookInFooter');
        $this->loader->add_action('rest_api_init', $this->publicView, 'registerApiRoutes');
        $this->loader->add_action('woocommerce_add_to_cart', $this->publicView, 'addToCartEvent', 10, 6);
        $this->loader->add_action('woocommerce_ajax_added_to_cart', $this->publicView, 'sessionStoreForProductAjaxAdded', 10, 1);
        $this->loader->add_filter('woocommerce_add_to_cart_fragments', $this->publicView, 'addToCartEventAjaxCall', 10, 1);
    }

	public function run()
	{
		$this->loader->run();
	}

	public function get_plugin_name()
	{
		return $this->pluginName;
	}

	public function get_loader()
	{
		return $this->loader;
	}

	public function get_version()
	{
		return $this->version;
	}
}
