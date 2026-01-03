# MutedBoard Framework - AI Coding Guide

# Commit Guidelines
- When creating commits: use this style:
"[Component name]: Brief description of change"
  - Example: "Router: Fix route matching bug with named parameters"

## Architecture Overview

This is a **custom lightweight PHP MVC framework** (not Laravel/Symfony). Key components:

- **Singleton App** ([core/App.php](core/App.php)) - Central application instance managing router and config
- **Custom Router** ([core/Router.php](core/Router.php)) - Regex-based URL matching with named parameters
- **PSR-4 Autoloading** - Manual implementation in [public/index.php](public/index.php), converts `Core\` → `core/` and `App\` → `app/`
- **Simple Template Engine** ([core/Template.php](core/Template.php)) - Custom syntax (not Twig/Blade)
- **Static Model Methods** - All database operations use static methods (e.g., `Thread::findById()`)

## Critical Controller Pattern

**ALWAYS call `parent::__construct($route_params)` in controller constructors:**

```php
public function __construct($route_params = [])
{
    parent::__construct($route_params);  // REQUIRED - sets $this->route_params
    // Your auth/validation logic here
}
```

**Why:** Route parameters (`{id:\d+}`) are passed to the constructor and stored in `$this->route_params`. Missing this call means no access to URL params like `$this->route_params['id']`.

## Routing System

Routes defined in [config/routes.php](config/routes.php) use regex patterns:

```php
// Named parameter with constraint
$router->add('dashboard/thread/{id:\d+}', ['controller' => 'Dashboard', 'action' => 'thread']);

// Generic patterns (LAST in routes.php - order matters!)
$router->add('{controller}/{action}');
$router->add('{controller}/{action}/{id:\d+}');
```

**Route matching is FIRST-MATCH** - specific routes must come before generic ones.

Parameter conversion in [Router.php](core/Router.php#L17-L19):
- `{id}` → `(?P<id>[a-z-]+)` (alphanumeric + hyphens)
- `{id:\d+}` → `(?P<id>\d+)` (digits only)

## Database Patterns

All models extend `Core\Model` and use **static methods only**:

```php
// Standard pattern - see Thread.php
public static function findById($id)
{
    $db = static::getDB();  // Gets singleton PDO connection
    $stmt = $db->prepare('SELECT * FROM threads WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
```

**Always use:** 
- Prepared statements with `bindValue()`
- `PDO::PARAM_INT` for integers, `PDO::PARAM_STR` for strings
- `PDO::FETCH_ASSOC` for associative arrays
- LEFT JOINs to include related data (see [Thread.php](app/Models/Thread.php#L14-L24))

## View Rendering Methods

Three distinct approaches - know when to use each:

```php
// 1. Simple PHP template (no layout)
View::render('home/index', ['title' => 'Home']);

// 2. With layout wrapper - MOST COMMON
View::renderWithTemplate('dashboard/index', 'default', ['title' => 'Dashboard']);
// Wraps content in app/views/layouts/default.php

// 3. Custom template engine (legacy - avoid for new code)
$template = new Template('home/index');
```

**Layout mechanism:** Content rendered first, then passed as `$content` variable to layout file.

## Action Method Naming

Controller methods **MUST** end with `Action` suffix:

```php
// URL: /dashboard/thread/5
public function threadAction()  // NOT thread()
{
    $id = $this->route_params['id'];
    // ...
}
```

The base `Controller::__call()` magic method in [core/Controller.php](core/Controller.php#L21-L33) appends `Action` and calls the method.

## Error Handling & Debugging

**Development mode** ([config/app.php](config/app.php)):
```php
'show_errors' => true  // Shows detailed error pages
```

**Production mode:**
```php
'show_errors' => false  // Logs to logs/YYYY-MM-DD.txt, shows app/views/404.html or 500.html
```

**Debugging route params:**
```php
error_log("Debug: " . print_r($this->route_params, true));
// Logs to logs/ directory when show_errors=false
```

## Session & Authentication

Sessions started globally in [public/index.php](public/index.php#L30-L32). Auth pattern in [Dashboard.php](app/Controllers/Dashboard.php):

```php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}
```

**Flash messages** stored in `$_SESSION['success']` or `$_SESSION['errors']` array, cleared after display.

## Development Workflow

**Start local server:**
```bash
cd public && php -S localhost:8000
```

**Database config:** [config/database.php](config/database.php) - PDO MySQL connection settings

**Route testing:** Check [logs/](logs/) directory for error_log() output when debugging route params

## Common Gotchas

1. **Missing parent constructor** - Controllers that override `__construct()` must call parent
2. **Route order matters** - Specific routes before generic patterns
3. **Action suffix required** - `indexAction()` not `index()`
4. **Static models only** - No `$this` in model methods, use `static::getDB()`
5. **Manual autoloader** - Converts namespace case: `Core\App` → `core/App.php` (lowercase dir)
