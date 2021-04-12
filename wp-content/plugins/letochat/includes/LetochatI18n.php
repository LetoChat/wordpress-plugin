<?php

namespace Letochat\Includes;

class LetochatI18n
{
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			'letochat',
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);
	}
}
