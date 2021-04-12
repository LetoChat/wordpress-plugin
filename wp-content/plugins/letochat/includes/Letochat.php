<?php

namespace Letochat\Includes;

use Letochat\AdminView\LetochatAdmin;
use Letochat\PublicView\LetochatPublic;

class Letochat
{
	protected $loader;

	protected $i18n;

	protected $adminView;

	protected $publicView;

	protected $pluginName;

	protected $version;

	public function __construct()
	{
		if (defined('LETOCHAT_VERSION')) {
			$this->version = LETOCHAT_VERSION;
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
        $this->i18n = new LetochatI18n();

        $this->adminView = new LetochatAdmin($this->pluginName, $this->version);

        $this->publicView = new LetochatPublic($this->pluginName, $this->version);

		$this->loader = new LetochatLoader();

	}

	private function setLocale()
	{
		$plugin_i18n = new LetochatI18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	private function defineAdminHooks()
	{
		$this->loader->add_action('admin_enqueue_scripts', $this->adminView, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $this->adminView, 'enqueue_scripts');
	}

	private function definePublicHooks()
	{
		// Actions
		$this->loader->add_action('wp_enqueue_scripts', $this->publicView, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $this->publicView, 'enqueue_scripts');
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
