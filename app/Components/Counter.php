<?php

namespace App\Components;

use Core\Component;

/**
 * Example: Simple Counter Component
 * Demonstrates basic component functionality
 */
class Counter extends Component
{
    protected $view = 'counter';

    protected function mount()
    {
        $this->setState('count', $this->prop('initial', 0));
    }

    public function increment($params = [])
    {
        $current = $this->getState('count', 0);
        $this->setState('count', $current + 1);
        
        return $this->refresh();
    }

    public function decrement($params = [])
    {
        $current = $this->getState('count', 0);
        $this->setState('count', $current - 1);
        
        return $this->refresh();
    }

    public function reset($params = [])
    {
        $this->setState('count', 0);
        
        return $this->refresh();
    }

    public function setCount($params = [])
    {
        $value = (int)($params['value'] ?? 0);
        $this->setState('count', $value);
        
        return [
            'success' => true,
            'message' => "Count set to {$value}",
            'html' => $this->render(),
            'state' => $this->state
        ];
    }
}
