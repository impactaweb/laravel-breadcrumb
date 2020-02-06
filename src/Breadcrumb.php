<?php

namespace Impactaweb\Breadcrumb;

use View;

class Breadcrumb {

    public $items = [];
    public $parameters;

    /**
     * Starts breadcrumb and find in the request hierarchy
     * if there is implemented route to add it automatically.
     */
    public function __construct()
    {
        $route = request()->route();
        $action = $route->getAction();
        $this->parameters = $route->parameters();

        if (!isset($action['name'])) {
            $action['name'] = [];
        }

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
        return self::pushArray([[$title, $url, $isRoute]]);
    }

    /**
     * Static function to create a breadcrumb with multiple items
     */
    public static function pushArray(array $pushList)
    {
        $breadcrumb = new Breadcrumb();
        foreach ($pushList as $item) {
            $title = $item[0];
            $url = $item[1] ?? "";
            $isRoute = $item[2] ?? true;
            $breadcrumb->add($title, $url, $isRoute);
        }
        $breadcrumb->render();
    }

    /**
     * Render the breadcrumb and sent it to Blade (View::share)
     */
    public function render()
    {
        View::share('breadcrumb', 
            view(config('breadcrumb.view'), ['items' => $this->items])->render()
        );
    }

}