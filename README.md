# Breadcrumb
Simple Laravel Breadcrumb

## Installation

1. `composer.json` file:
```json
(...)
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:impactaweb/laravel-breadcrumb.git"
    }
]
(...)
```

2. Run `composer install impactaweb/laravel-breadcrumb`

3. Add `Impactaweb\Breadcrumb\ServiceProvider::class` to your providers list on `/config/app.php`.

## Usage

```php
<?php
Breadcrumb::push("Admin", "admin.index");
Breadcrumb::push("Users", "/admin/users", false);
Breadcrumb::pushList([
        // same as ["Title", route('route.name'), false]:
        ["Title", "route.name"], 
        ["Users", "/admin/users", false]
    ]);
```

## Customized template

Create a /config/breadcrumb.php file with the following code:

```php
<?php

return [
    'view' => 'path.to.your.blade.view'
]
```

The variable `$items` will have "title" and "url" list:
```
@foreach ($items as $item)
    <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
@endforeach
```

## Automatic breadcrumb from Laravel request hierarchy

Create a function with the route name. 
For example, the route `admin.something.users` (`/admin/something/users`) could be implemented like this:

### Extended class

Function to add "Admin" and "Something" to breadcrumb automatically:

```php
use Impactaweb\Breadcrumb\Breadcrumb;

class MyBreadcrumb extends Breadcrumb {

    public function admin()
    {
        $this->add("Admin", 'admin.index');
    }

    public function something($parameters)
    {
        // Do something with
        // $parameters variable
        // ...
        $url = "/fixed/ur/to/something";

        // false means: do not use route() function
        $this->add("Something", $url, false);
    }

}
```

### Breadcrumb call:

```php
MyBreadcrumb::push("Users", "users");
```
