<?php

namespace Core;

/**
 * Base Component class for creating reusable, interactive components
 * Components can declare fields, methods, and render views
 * Automatically handles AJAX calls and state management
 */
abstract class Component
{
    /**
     * Unique component instance ID
     */
    protected $componentId;

    /**
     * Component state data
     */
    protected $state = [];

    /**
     * Component properties passed from parent
     */
    protected $props = [];

    /**
     * View template for this component
     */
    protected $view;

    /**
     * Constructor
     * 
     * @param array $props Properties passed to component
     * @param string|null $componentId Optional component ID
     */
    public function __construct(array $props = [], $componentId = null)
    {
        $this->props = $props;
        $this->componentId = $componentId ?? $this->generateComponentId();
        $this->mount();
    }

    /**
     * Called when component is first created
     * Override to initialize component state
     */
    protected function mount()
    {
        // Override in child components
    }

    /**
     * Generate unique component ID
     */
    protected function generateComponentId()
    {
        return 'component-' . uniqid();
    }

    /**
     * Get component ID
     */
    public function getId()
    {
        return $this->componentId;
    }

    /**
     * Get component class name (short name)
     */
    public function getComponentName()
    {
        $class = get_class($this);
        return substr($class, strrpos($class, '\\') + 1);
    }

    /**
     * Set state property
     */
    protected function setState($key, $value)
    {
        $this->state[$key] = $value;
    }

    /**
     * Get state property
     */
    protected function getState($key, $default = null)
    {
        return $this->state[$key] ?? $default;
    }

    /**
     * Get all state
     */
    public function getStateArray()
    {
        return $this->state;
    }

    /**
     * Get prop value
     */
    protected function prop($key, $default = null)
    {
        return $this->props[$key] ?? $default;
    }

    /**
     * Render the component
     * 
     * @return string HTML output
     */
    public function render()
    {
        ob_start();
        
        // Make state and props available to view
        extract($this->state);
        extract($this->props);
        
        // Component metadata
        $componentId = $this->componentId;
        $componentName = $this->getComponentName();
        $props = $this->props; // Keep original props array accessible
        $state = $this->state; // Keep original state array accessible
        
        // Include the view file
        $viewFile = dirname(__DIR__) . '/app/views/components/' . $this->view . '.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "<div class='component-error'>Component view not found: {$this->view}</div>";
        }
        
        return ob_get_clean();
    }

    /**
     * Handle AJAX method calls
     * 
     * @param string $method Method name to call
     * @param array $params Parameters for the method
     * @return array Response data
     */
    public function handleAjax($method, $params = [])
    {
        // Check if method exists and is public
        if (!method_exists($this, $method)) {
            return [
                'success' => false,
                'error' => 'Method not found: ' . $method
            ];
        }

        $reflection = new \ReflectionMethod($this, $method);
        if (!$reflection->isPublic() || $reflection->isStatic()) {
            return [
                'success' => false,
                'error' => 'Method not accessible: ' . $method
            ];
        }

        try {
            // Call the method
            $result = $this->$method($params);
            
            // If method returns array, use it; otherwise wrap in success response
            if (is_array($result)) {
                return $result;
            }
            
            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Re-render the component and return HTML
     * Used for AJAX updates
     */
    public function refresh()
    {
        return [
            'success' => true,
            'html' => $this->render(),
            'state' => $this->state
        ];
    }

    /**
     * Create component instance from AJAX request
     * 
     * @param string $componentClass Full class name
     * @param array $props Component props
     * @param string $componentId Component ID
     * @return Component|null
     */
    public static function createFromAjax($componentClass, $props = [], $componentId = null)
    {
        if (!class_exists($componentClass)) {
            return null;
        }

        return new $componentClass($props, $componentId);
    }
}
