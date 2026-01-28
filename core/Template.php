<?php

namespace Core;

class Template
{
    protected $template;
    protected $data = [];
    protected $blocks = [];
    protected $currentBlock;

    /**
     * Constructor
     */
    public function __construct($template)
    {
        $this->template = $template;
    }

    /**
     * Set data for template
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * Set multiple data
     */
    public function setMultiple($data)
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    /**
     * Render the template
     */
    public function render()
    {
        extract($this->data, EXTR_SKIP);

        $templateFile = dirname(__DIR__) . '/app/views/' . $this->template;

        if (!is_readable($templateFile)) {
            throw new \Exception("Template file $templateFile not found");
        }

        ob_start();
        require $templateFile;
        $content = ob_get_clean();

        return $this->parseTemplate($content);
    }

    /**
     * Parse template syntax
     */
    protected function parseTemplate($content)
    {
        // Parse {{ variable }}
        $content = preg_replace_callback('/\{\{\s*(.+?)\s*\}\}/', function($matches) {
            $var = trim($matches[1]);
            
            // Handle filters like {{ name|upper }}
            if (strpos($var, '|') !== false) {
                $parts = explode('|', $var);
                $varName = trim($parts[0]);
                $filter = trim($parts[1]);
                
                return "<?php echo \$this->filter(" . $this->parseVariable($varName) . ", '$filter'); ?>";
            }
            
            return "<?php echo htmlspecialchars(" . $this->parseVariable($var) . ", ENT_QUOTES, 'UTF-8'); ?>";
        }, $content);

        // Parse {{{ raw_variable }}}
        $content = preg_replace_callback('/\{\{\{\s*(.+?)\s*\}\}\}/', function($matches) {
            $var = trim($matches[1]);
            return "<?php echo " . $this->parseVariable($var) . "; ?>";
        }, $content);

        // Parse {% if condition %}
        $content = preg_replace('/\{%\s*if\s+(.+?)\s*%\}/', '<?php if (\1): ?>', $content);
        $content = preg_replace('/\{%\s*elseif\s+(.+?)\s*%\}/', '<?php elseif (\1): ?>', $content);
        $content = preg_replace('/\{%\s*else\s*%\}/', '<?php else: ?>', $content);
        $content = preg_replace('/\{%\s*endif\s*%\}/', '<?php endif; ?>', $content);

        // Parse {% foreach %}
        $content = preg_replace('/\{%\s*foreach\s+(.+?)\s+as\s+(.+?)\s*%\}/', '<?php foreach (\1 as \2): ?>', $content);
        $content = preg_replace('/\{%\s*endforeach\s*%\}/', '<?php endforeach; ?>', $content);

        // Parse {% for %}
        $content = preg_replace('/\{%\s*for\s+(.+?)\s*%\}/', '<?php for (\1): ?>', $content);
        $content = preg_replace('/\{%\s*endfor\s*%\}/', '<?php endfor; ?>', $content);

        // Parse {% while %}
        $content = preg_replace('/\{%\s*while\s+(.+?)\s*%\}/', '<?php while (\1): ?>', $content);
        $content = preg_replace('/\{%\s*endwhile\s*%\}/', '<?php endwhile; ?>', $content);

        return $content;
    }

    /**
     * Parse variable notation (supports $var, $array['key'], $obj->property)
     */
    protected function parseVariable($var)
    {
        // If already has $, return as is
        if (strpos($var, '$') === 0) {
            return $var;
        }

        // Handle array notation: users.0.name -> $users[0]['name']
        if (strpos($var, '.') !== false) {
            $parts = explode('.', $var);
            $result = '$' . array_shift($parts);
            
            foreach ($parts as $part) {
                if (is_numeric($part)) {
                    $result .= "[$part]";
                } else {
                    $result .= "['$part']";
                }
            }
            
            return $result;
        }

        return '$' . $var;
    }

    /**
     * Apply filters to variables
     */
    public function filter($value, $filter)
    {
        switch ($filter) {
            case 'upper':
                return strtoupper($value);
            case 'lower':
                return strtolower($value);
            case 'capitalize':
                return ucfirst($value);
            case 'title':
                return ucwords($value);
            case 'trim':
                return trim($value);
            case 'json':
                return json_encode($value);
            default:
                return $value;
        }
    }

    /**
     * Include a partial template
     */
    public function partial($template, $data = [])
    {
        $partial = new self($template);
        $partial->setMultiple(array_merge($this->data, $data));
        echo $partial->render();
    }
}
