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

		wp_enqueue_style($this->pluginName, PLUGIN_LETO_CHAT_URL . 'admin/css/style.css', [], $this->version, 'all');
	}

	public function enqueue_scripts()
	{
		if ($hook != 'toplevel_page_letochat') {
			return;
		}

		wp_enqueue_script($this->pluginName, '', ['jquery'], $this->version, false);
	}

	public function adminMenu()
	{
		$this->adminViewFacade->adminMenu();
	}
}
