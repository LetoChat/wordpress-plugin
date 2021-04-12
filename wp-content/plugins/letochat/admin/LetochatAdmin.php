<?php

namespace Letochat\AdminView;

use Letochat\AdminView\Business\LetochatFacade as LetochatAdminViewFacade;
use Letochat\PublicView\Business\LetochatFacade as LetochatPublicViewFacade;

class LetochatAdmin
{
	private $pluginName;

	private $version;

	private $adminViewFacade;

	private $publicViewFacade;

	public function __construct($pluginName, $version)
	{
		$this->pluginName = $pluginName;
		$this->version = $version;
		$this->adminViewFacade = LetochatAdminViewFacade::getInstance();
		$this->publicViewFacade = LetochatPublicViewFacade::getInstance();
	}

	public function enqueue_styles()
	{
		wp_enqueue_style($this->pluginName, '', [], $this->version, 'all');
	}

	public function enqueue_scripts()
	{
		wp_enqueue_script($this->pluginName, '', ['jquery'], $this->version, false );
	}
}
