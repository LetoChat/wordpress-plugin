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
}
