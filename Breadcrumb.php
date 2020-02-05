<?php

namespace App\Resources;

use View;

class Breadcrumb {

    public $items = [];
    public $parameters;

    /**
     * Starts breadcrumb and find in the request hierarchy
     * if there is implemented route to add it automatically.
     * 
     * How to implement a automatic route:
     * - Create a function with the route name. Example:
     * 
     * Route: admin.users
     * You call: Breadcrumb::push("Users", "users");
     * Function to add "Admin" to breadcrumb automatically:
     * 
     * public function admin($parameters)
     * {
     *      $this->add("Admin", "/admin", false);
     * }
     */
    public function __construct()
    {
        $route = request()->route();
        $action = $route->getAction();
        $this->parameters = $route->parameters();

        // The $action['name'], when there is only one, come as string.
        foreach ((array)$action['name'] as $name) {
            $name = trim($name,'.');
            if (method_exists($this, $name)) {
                $this->$name($this->parameters);
            }
        }
    }

    /**
     * Adds item to breadcrumb list
     */
    public function add($title, $url, bool $isRoute = true)
    {
        if ($isRoute && !empty($url)) {
            $url = strtok(route($url, $this->parameters), '?');
        }
        $this->items[] = compact('title', 'url');
    }

    /**
     * Static function to create a single breadcrumb
     */
    public static function push(string $title, string $url = "", bool $isRoute = true)
    {
        $breadcrumb = new Breadcrumb();
        $breadcrumb->add($title, $url, $isRoute);
        $breadcrumb->render();
    }

    /**
     * Static function to create a breadcrumb with multiple items
     */
    public static function pushArray(array $pushList, bool $isRoute = true)
    {
        $breadcrumb = new Breadcrumb();
        foreach ($pushList as $item) {
            $title = $item[0];
            $url = $item[1] ?? "";
            $breadcrumb->add($title, $url, $isRoute);
        }
        $breadcrumb->render();
    }

    /**
     * Render the breadcrumb and sent it to Blade (View::share)
     */
    public function render()
    {
        $html = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
        $count = count($this->items);
        foreach ($this->items as $i => $item) {
            $html .= '<li class="breadcrumb-item';
            if ($i === ($count - 1)) {
                $html .= ' active';
            }
            $html .= '">';
            if (empty($item['url'])) {
                $html .= $item['title'];
            } else {
                $html .= '<a href="'.$item['url'].'">'.$item['title'].'</a>';
            }
            $html .= "</li>";
        }
        $html .= "</ol></nav>";

        View::share('breadcrumb', $html);
    }

}