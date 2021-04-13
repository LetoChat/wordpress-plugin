<?php

namespace LetoChat\Includes;

trait LetoChatHelper
{
    /**
     * @param string $path
     * @param array $args
     * @return false|string
     */
    public function renderView(string $path, array $view)
    {
        ob_start();
        include($path);
        $template = ob_get_contents();
        ob_end_clean();

        return $template;
    }

    public function myPrint($var)
    {
        echo '<pre>' . print_r($var, true) . '</pre>';
    }
}