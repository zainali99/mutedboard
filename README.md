# MutedBoard - open source forum - blog software

### Status: Alpha version
## Not production-ready; expect frequent breaking changes.
TODO for stable release 1.0:
- [x] installer wizard 60% (need refactor)
- [ ] ORM / query layer
- [ ] basic Migrations 
- [x] component stable sytem (80%
- [ ]  frontend
  - [ ] index 
  - [ ] threads views
  - [ ] threds management
  - [ ] comments management
  - [ ] sitemap generator
- [ ] basic importer from other forum software like mybb, phpbb etc
- [ ] stats
- [ ] cache system initially file based, later add support for redis etc
- [ ] plugins/addons system
      

A lightweight custom PHP MVC framework with template engine support.

## Features

- ✅ Custom Router with flexible URL patterns
- ✅ MVC Architecture (Model-View-Controller)
- ✅ Custom Template Engine with syntax like `{{ variable }}`
- ✅ PDO Database Support
- ✅ Error Handling and Custom Error Pages
- ✅ PSR-4 Autoloading
- ✅ Clean URL Routing

## Directory Structure

```
mutedboard/
├── app/
│   ├── Controllers/      # Application controllers
│   ├── Models/          # Database models
│   └── views/           # View templates
│       ├── layouts/     # Layout templates
│       └── home/        # Page views
├── core/                # Framework core classes
│   ├── App.php
│   ├── Controller.php
│   ├── Model.php
│   ├── Router.php
│   ├── View.php
│   ├── Template.php
│   └── Error.php
├── config/              # Configuration files
│   ├── app.php
│   ├── database.php
│   └── routes.php
├── public/              # Web root (point your server here)
│   ├── index.php        # Front controller
│   ├── .htaccess        # Apache rewrite rules
│   ├── css/
│   └── js/
└── logs/                # Error logs (created automatically)
```

## Installation

1. Clone or download this framework to your web server
2. Point your web server's document root to the `public/` directory
3. Ensure `mod_rewrite` is enabled (for Apache)
4. Update database configuration in `config/database.php`
5. Configure routes in `config/routes.php`

## Server Configuration

### Apache
Make sure `.htaccess` is in the `public/` directory and `mod_rewrite` is enabled.

### Nginx
Add this to your server block:
```nginx
location / {
    try_files $uri $uri/ /index.php?url=$uri&$args;
}
```

### PHP Built-in Server (Development)
```bash
cd public
php -S localhost:8000
```

## Creating Controllers

Controllers should be placed in `app/Controllers/` and extend `Core\Controller`:

```php
<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;

class Example extends Controller
{
    public function indexAction()
    {
        $data = ['title' => 'Example Page'];
        View::renderWithTemplate('example/index.php', 'default', $data);
    }
}
```

## Creating Models

Models should be placed in `app/Models/` and extend `Core\Model`:

```php
<?php

namespace App\Models;

use Core\Model;
use PDO;

class Example extends Model
{
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM table_name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
```

## Creating Views

Views are PHP templates stored in `app/views/`. Use layouts for consistent page structure.

### Simple View (app/views/example/index.php):
```php
<h1><?= htmlspecialchars($title) ?></h1>
<p>Content goes here</p>
```

### Layout (app/views/layouts/default.php):
```php
<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'My App' ?></title>
</head>
<body>
    <?= $content ?>
</body>
</html>
```

## Template Engine

The custom template engine supports clean syntax:

```php
{# Variables (auto-escaped) #}
{{ name }}
{{ user.email }}

{# Raw output (unescaped) #}
{{{ html_content }}}

{# Filters #}
{{ name|upper }}
{{ text|lower }}

{# Conditionals #}
{% if user %}
    Welcome, {{ user.name }}!
{% else %}
    Please log in
{% endif %}

{# Loops #}
{% foreach $items as $item %}
    <li>{{ item.name }}</li>
{% endforeach %}
```

## Routing

Define routes in `config/routes.php`:

```php
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{action}/{id:\d+}');
$router->add('posts/{id:\d+}', ['controller' => 'Posts', 'action' => 'show']);
```

## Database Configuration

Update `config/database.php` with your database credentials:

```php
return [
    'host' => 'localhost',
    'dbname' => 'your_database',
    'username' => 'your_username',
    'password' => 'your_password',
    'charset' => 'utf8mb4'
];
```

## Rendering Views

Three ways to render views:

```php
// 1. Simple render (no layout)
View::render('home/index.php', ['title' => 'Home']);

// 2. With layout template
View::renderWithTemplate('home/index.php', 'default', ['title' => 'Home']);

// 3. Using custom template engine
$template = new Template('home/index.php');
$template->set('title', 'Home')->render();
```

## Error Handling

- Set `show_errors` to `true` in `config/app.php` during development
- Set to `false` in production (errors will be logged to `logs/` directory)
- Custom 404 and 500 error pages in `app/views/`

## License

This is a custom framework for educational and project use.
