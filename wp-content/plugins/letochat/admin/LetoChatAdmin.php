<?php

namespace LetoChat\AdminView;

use LetoChat\AdminView\Business\LetoChatFacade as LetoChatAdminViewFacade;
use LetoChat\PublicView\Business\LetoChatFacade as LetoChatPublicViewFacade;

class LetoChatAdmin
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

	public function enqueue_styles($hook)
	{
		if ($hook != 'toplevel_page_letochat') {
			return;
		}

		wp_enqueue_style('bootstrap', PLUGIN_LETO_CHAT_URL . 'lib/bootstrap-4.3.1/css/bootstrap.min.css', [], '4.3.1', 'all');
		wp_enqueue_style('notiflix', PLUGIN_LETO_CHAT_URL . 'lib/notiflix-2.7.0/notiflix.min.css', [], '2.7.0', 'all');
		wp_enqueue_style('toggle-switchy', PLUGIN_LETO_CHAT_URL . 'lib/toggle-switchy/toggle-switchy.css', [], '1.14', 'all');
		wp_enqueue_style($this->pluginName, PLUGIN_LETO_CHAT_URL . 'admin/css/letochat.css', [], $this->version, 'all');
	}

	public function enqueue_scripts($hook)
	{
		if ($hook != 'toplevel_page_letochat') {
			return;
		}

        wp_enqueue_script('notiflix', PLUGIN_LETO_CHAT_URL . 'lib/notiflix-2.7.0/notiflix.min.js', [], '2.7.0', false);
        wp_register_script($this->pluginName, PLUGIN_LETO_CHAT_URL . 'admin/js/letochat.js', ['jquery'], $this->version, false);
        wp_localize_script($this->pluginName, 'ajax_letochat_admin_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('ajax_letochat_public'),
            'messages' => [
                'please_wait' => __('Please wait...', 'letochat'),
            ]
        ]);
        wp_enqueue_script($this->pluginName);
	}

	public function adminMenu()
	{
		$this->adminViewFacade->adminMenu();
	}

    public function checkLetoChatData()
    {
        $this->adminViewFacade->checkLetoChatData();
    }
}
