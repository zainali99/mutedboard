<?php

namespace Core;

class View
{
    /**
     * Render a view file
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/app/views/$view";

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using the template engine
     */
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/app/views');
            $twig = new \Twig\Environment($loader);
        }

        echo $twig->render($template, $args);
    }

    /**
     * Render using custom simple template engine
     */
    public static function renderWithTemplate($view, $layout = 'default', $args = [])
    {
        $content = self::getViewContent($view, $args);
        $layoutFile = dirname(__DIR__) . "/app/views/layouts/$layout.php";

        if (is_readable($layoutFile)) {
            extract(['content' => $content] + $args, EXTR_SKIP);
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    /**
     * Get view content as string
     */
    protected static function getViewContent($view, $args = [])
    {
        extract($args, EXTR_SKIP);
        
        $file = dirname(__DIR__) . "/app/views/$view";

        if (is_readable($file)) {
            ob_start();
            require $file;
            return ob_get_clean();
        } else {
            throw new \Exception("$file not found");
        }
    }
}
