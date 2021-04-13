<?php

namespace LetoChat\Includes;

class LetoChatI18n
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
