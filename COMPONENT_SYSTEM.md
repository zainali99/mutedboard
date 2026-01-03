# MutedBoard Component System

## Overview

The Component System is a powerful feature that allows developers to create reusable, interactive components with automatic AJAX handling, state management, and real-time updates. Components work seamlessly between backend (PHP) and frontend (JavaScript) without requiring manual JavaScript coding for each component.

## Key Features

- **Declarative Components**: Define components in PHP with fields and methods
- **Automatic AJAX Handling**: Frontend automatically calls PHP methods via AJAX
- **State Management**: Built-in state management system
- **Auto-Rendering**: Components automatically re-render on state changes
- **Event System**: Custom events for component interactions
- **Form Handling**: Automatic form submission and validation
- **No Boilerplate JS**: Minimal JavaScript required - the framework handles it all

## Architecture

### Backend (PHP)

- **Base Component Class** ([core/Component.php](core/Component.php)): Abstract class that all components extend
- **Component Classes** (app/Components/): Your custom component implementations
- **Component Views** (app/views/components/): HTML templates for components
- **AJAX Handler** ([app/Controllers/Ajax.php](app/Controllers/Ajax.php#L208)): Routes component method calls

### Frontend (JavaScript)

- **Component System Library** ([public/js/components.js](public/js/components.js)): Handles all frontend interactions automatically

## Creating a Component

### 1. Create Component Class

Create a new PHP class in `app/Components/`:

```php
<?php

namespace App\Components;

use Core\Component;
use App\Models\YourModel;

class YourComponent extends Component
{
    /**
     * View template name (maps to app/views/components/your-component.php)
     */
    protected $view = 'your-component';

    /**
     * Initialize component (called when component is created)
     */
    protected function mount()
    {
        // Set initial state
        $this->setState('items', []);
        $this->setState('count', 0);
        
        // Get props passed from parent
        $id = $this->prop('id');
        
        // Load initial data
        $this->loadData($id);
    }

    /**
     * Public methods can be called via AJAX
     */
    public function loadData($params = [])
    {
        $id = $params['id'] ?? $this->prop('id');
        $items = YourModel::findById($id);
        
        $this->setState('items', $items);
        $this->setState('count', count($items));
        
        // Return refreshed component
        return $this->refresh();
    }

    public function addItem($params = [])
    {
        // Validate
        if (empty($params['name'])) {
            return [
                'success' => false,
                'error' => 'Name is required'
            ];
        }

        // Create item
        $id = YourModel::create($params);
        
        if (!$id) {
            return [
                'success' => false,
                'error' => 'Failed to create item'
            ];
        }

        // Reload data
        $this->loadData();

        return [
            'success' => true,
            'message' => 'Item added successfully',
            'html' => $this->render(),
            'state' => $this->state
        ];
    }

    public function deleteItem($params = [])
    {
        $itemId = $params['item_id'] ?? null;
        
        if (!$itemId) {
            return ['success' => false, 'error' => 'ID required'];
        }

        YourModel::delete($itemId);
        $this->loadData();

        return [
            'success' => true,
            'message' => 'Item deleted',
            'html' => $this->render(),
            'state' => $this->state
        ];
    }
}
```

### 2. Create Component View

Create template in `app/views/components/your-component.php`:

```php
<div 
    class="your-component" 
    data-component="YourComponent" 
    data-component-id="<?= htmlspecialchars($componentId) ?>"
    data-props='<?= json_encode($props) ?>'
>
    <h3>Component Title (<?= $count ?>)</h3>

    <!-- Add Form -->
    <form data-component-form data-action="addItem" data-reset-on-success>
        <input type="text" name="name" placeholder="Enter name" required>
        <button type="submit">Add</button>
    </form>

    <!-- Items List -->
    <div class="items-list">
        <?php foreach ($items as $item): ?>
        <div class="item">
            <span><?= htmlspecialchars($item['name']) ?></span>
            
            <!-- Action Button -->
            <button 
                data-action="deleteItem"
                data-item-id="<?= $item['id'] ?>"
                onclick="return confirm('Delete this item?')"
            >
                Delete
            </button>
        </div>
        <?php endforeach; ?>
    </div>
</div>
```

### 3. Use Component in Views

In any view file (e.g., [app/views/dashboard/thread.php](app/views/dashboard/thread.php#L274)):

```php
<?php
use App\Components\YourComponent;

$component = new YourComponent([
    'id' => $someId,
    'title' => 'My Component'
]);

echo $component->render();
?>
```

## Component API Reference

### Component Properties

- `$componentId`: Unique component instance ID
- `$state`: Component state data (array)
- `$props`: Properties passed from parent (array)
- `$view`: View template name (string)

### Component Methods

#### `mount()`
Called when component is first created. Override to initialize state.

```php
protected function mount()
{
    $this->setState('key', 'value');
}
```

#### `setState($key, $value)`
Set a state property.

```php
$this->setState('count', 10);
```

#### `getState($key, $default = null)`
Get a state property.

```php
$count = $this->getState('count', 0);
```

#### `prop($key, $default = null)`
Get a prop value.

```php
$threadId = $this->prop('thread_id');
```

#### `render()`
Render the component HTML. Automatically called.

```php
return $component->render();
```

#### `refresh()`
Re-render component and return response with new HTML.

```php
return $this->refresh();
```

## Frontend API

### HTML Attributes

#### `data-component`
Identifies the component type.

```html
<div data-component="CommentsBox">
```

#### `data-component-id`
Unique component instance ID.

```html
<div data-component-id="<?= $componentId ?>">
```

#### `data-props`
JSON-encoded props passed to component.

```html
<div data-props='{"thread_id": 123}'>
```

#### `data-action`
Declares an action to call on click.

```html
<button data-action="deleteComment" data-comment-id="5">Delete</button>
```

#### `data-component-form`
Marks a form for automatic AJAX handling.

```html
<form data-component-form data-action="addComment">
```

#### `data-reset-on-success`
Resets form after successful submission.

```html
<form data-component-form data-reset-on-success>
```

### JavaScript API

The component system is automatically available as `window.componentSystem`.

#### Call Component Method

```javascript
componentSystem.call('component-123', 'methodName', {
    param1: 'value1',
    param2: 'value2'
}).then(result => {
    if (result.success) {
        console.log('Success:', result);
    }
});
```

#### Refresh Component

```javascript
componentSystem.refresh('component-123').then(result => {
    console.log('Component refreshed');
});
```

#### Listen to Component Events

```javascript
document.addEventListener('action:addComment', (event) => {
    console.log('Comment added:', event.detail);
});
```

## Example: CommentsBox Component

The [CommentsBox component](app/Components/CommentsBox.php) is a full-featured example that demonstrates:

- Loading comments from database
- Adding new comments via form
- Deleting comments with confirmation
- Permission checks
- Automatic re-rendering
- State management

### Usage

```php
<?php
use App\Components\CommentsBox;

$commentsBox = new CommentsBox([
    'thread_id' => $thread['id']
]);

echo $commentsBox->render();
?>
```

### Features Demonstrated

1. **Auto-loading data** in `mount()`
2. **Form submission** with `addComment()`
3. **Action buttons** with `deleteComment()`
4. **Validation** and error handling
5. **Permission checks** (user must own comment)
6. **Automatic refresh** after actions

## Database Migration

Comments table is created via migration:

```bash
# Run migration
mysql -u username -p database_name < migrations/003_create_comments_table.sql
```

## Best Practices

1. **Keep components focused**: Each component should have a single responsibility
2. **Use props for configuration**: Pass data via props, not globals
3. **Return structured responses**: Always return `['success' => bool, ...]`
4. **Validate user input**: Check permissions and validate data
5. **Use refresh()**: Return `$this->refresh()` to update the component
6. **Handle errors gracefully**: Return error messages in responses
7. **Style in view**: Keep CSS in component view files
8. **Test thoroughly**: Components are reusable, so test edge cases

## Security Considerations

- Component methods must be **public** to be callable via AJAX
- Always validate user permissions in component methods
- Sanitize user input before database operations
- Use prepared statements in model methods
- Check `$_SESSION['user_id']` for authentication
- Validate all parameters passed to methods

## Troubleshooting

### Component doesn't update
- Check browser console for JavaScript errors
- Verify AJAX route exists at `/ajax/component`
- Ensure component returns `$this->refresh()` or HTML

### Method not found error
- Verify method is `public` (not `protected` or `private`)
- Check method name matches `data-action` attribute
- Ensure method exists in component class

### State not persisting
- State only persists during single request
- For persistent state, store in database or session
- Component is recreated on each AJAX call

### Form not submitting
- Verify `data-component-form` attribute exists
- Check `data-action` points to valid method
- Ensure components.js is loaded

## Advanced Topics

### Custom Events

Components dispatch custom events that you can listen to:

```javascript
document.addEventListener('action:methodName', (event) => {
    console.log('Component ID:', event.detail.componentId);
    console.log('Result:', event.detail);
});
```

### Manual Component Updates

```javascript
// Get component
const component = componentSystem.getComponent('component-123');

// Update state locally
component.state.customValue = 'new value';

// Call method
componentSystem.call('component-123', 'refresh');
```

### Multiple Instances

You can have multiple instances of the same component on one page:

```php
$comments1 = new CommentsBox(['thread_id' => 1]);
$comments2 = new CommentsBox(['thread_id' => 2]);

echo $comments1->render();
echo $comments2->render();
```

Each gets a unique ID and works independently.

## Summary

The Component System provides a modern, reactive approach to building interactive features in MutedBoard without writing repetitive JavaScript. Simply define your component logic in PHP, create a view template, and the framework handles the rest!
