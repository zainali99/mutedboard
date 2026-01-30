<?php

namespace Core {
#namespace App;

class View
{
    /**
     * Render a view file
     */
    public static function render($view, $args = [])
    {
        extract(self::prepareViewVars($args), EXTR_SKIP);

        $file = dirname(__DIR__) . "/app/views/$view.php";

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
        $vars = self::prepareViewVars($args);
        $content = self::getViewContent($view, $vars);
        $layoutFile = dirname(__DIR__) . "/app/views/layouts/$layout.php";
        if (is_readable($layoutFile)) {
            extract(['content' => $content] + $vars, EXTR_SKIP);
            require $layoutFile;

        } else {
            echo $content;
        }
    }

    /**
        * Parse markdown to HTML with basic formatting, code blocks, and images
        */
    public static function markdown($text)
        {
           if (empty($text)) {
              return '';
           }

           // Escape HTML first to prevent XSS
           $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

           // Code blocks (```language\ncode\n```)
           $text = preg_replace_callback('/```(\w*)\n(.*?)\n```/s', function($matches) {
              $language = htmlspecialchars($matches[1]);
              $code = $matches[2];
              $langClass = $language ? ' class="language-' . $language . '"' : '';
              return '<pre><code' . $langClass . '>' . $code . '</code></pre>';
           }, $text);

           // Inline code (`code`)
           $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);

           // Images (![alt](url))
           $text = preg_replace('/!\[([^\]]*)\]\(([^\)]+)\)/', '<img src="$2" alt="$1">', $text);

           // Links ([text](url))
           $text = preg_replace('/\[([^\]]+)\]\(([^\)]+)\)/', '<a href="$2">$1</a>', $text);

           // Bold (**text** or __text__)
           $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
           $text = preg_replace('/__(.+?)__/', '<strong>$1</strong>', $text);

           // Italic (*text* or _text_)
           $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);
           $text = preg_replace('/_(.+?)_/', '<em>$1</em>', $text);

           // Headers (# H1, ## H2, etc.)
           $text = preg_replace('/^#### (.+)$/m', '<h4>$1</h4>', $text);
           $text = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $text);
           $text = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $text);
           $text = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $text);

           // Unordered lists (- item or * item)
           $text = preg_replace_callback('/^([\*\-] .+)(\n[\*\-] .+)*/m', function($matches) {
              $items = preg_replace('/^[\*\-] (.+)$/m', '<li>$1</li>', $matches[0]);
              return '<ul>' . $items . '</ul>';
           }, $text);

           // Ordered lists (1. item)
           $text = preg_replace_callback('/^(\d+\. .+)(\n\d+\. .+)*/m', function($matches) {
              $items = preg_replace('/^\d+\. (.+)$/m', '<li>$1</li>', $matches[0]);
              return '<ol>' . $items . '</ol>';
           }, $text);

           // Line breaks (double newline = paragraph, single = br)
           $text = preg_replace('/\n\n/', '</p><p>', $text);
           $text = preg_replace('/\n/', '<br>', $text);
           $text = '<p>' . $text . '</p>';

           // Clean up empty paragraphs
           $text = preg_replace('/<p><\/p>/', '', $text);
           $text = preg_replace('/<p>(<(?:h[1-6]|ul|ol|pre)>)/', '$1', $text);
           $text = preg_replace('/(<\/(?:h[1-6]|ul|ol|pre)>)<\/p>/', '$1', $text);

           return $text;
        }

    /**
     * Prepare view variables with common globals
     */
    protected static function prepareViewVars($args = [])
    {
        return [
            'currentLanguage' => self::getCurrentLanguage()
        ] + $args;
    }

    /**
     * Get current language
     */
    public static function getCurrentLanguage()
    {
        return App::getInstance()->getRouter()->getLanguage();
    }

    /**
     * Get translated string
     */
    public static function translate($key, $default = null)
    {
        $lang = self::getCurrentLanguage();
        $langFile = dirname(__DIR__) . "/app/i18n/{$lang}/{$lang}.php";
        
        if (file_exists($langFile)) {
            static $translations = [];
            if (!isset($translations[$lang])) {
                $translations[$lang] = require $langFile;
            }
            return $translations[$lang][$key] ?? $default ?? $key;
        }
        
        return $default ?? $key;
    }

    /**
     * Get view content as string
     */
    protected static function getViewContent($view, $args = [])
    {
        extract(self::prepareViewVars($args), EXTR_SKIP);
        
        $file = dirname(__DIR__) . "/app/views/$view.php";

        if (is_readable($file)) {
            ob_start();
            require $file;
            return ob_get_clean();
        } else {
            throw new \Exception("$file not found");
        }
    }
}

} // End of Core namespace

namespace {
    /**
     * Helper function for translations - shorthand for View::translate()
     */
    if (!function_exists('t')) {
        function t($key, $default = null) {
            return \Core\View::translate($key, $default);
        }
    }
}
