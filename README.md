# Breadcrumb
Simple Laravel Breadcrumb

## Get automatic routes from Laravel request hierarchy

Create a function with the route name. 
For example, the route `admin.something.users` (`/admin/something/users`) could be implemented like this:

### Extended class

Function to add "Admin" and "Something" to breadcrumb automatically:

```php
use Impactaweb\Breadcrumb;

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

        $this->add("Something", $url);
    }

}
```

### Breadcrumb call:

```php
MyBreadcrumb::push("Users", "users");
```
