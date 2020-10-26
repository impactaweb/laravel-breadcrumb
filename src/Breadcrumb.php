<?php

namespace Impactaweb\Breadcrumb;

class Breadcrumb {

    public $items = [];
    public $parameters;
    private static $instance;

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
     * Get singleton instance
     *
     * @return void
     */
    public static function getInstance()
    {
        return !isset(self::$instance)
            ? self::$instance = new self
            : self::$instance;
    }

    /**
     * Adds item to breadcrumb list
     *
     * @param string $title
     * @param string|null $url
     * @param boolean $isRoute
     * @return void
     */
    public function add(string $title, ?string $url, bool $isRoute = true)
    {
        if ($isRoute && !empty($url)) {
            try {
                $route = route($url, $this->parameters, false);
            } catch (\Exception $e) {
                $route = '#';
            }
            $url = strtok($route, '?');
        }
        $this->items[] = compact('title', 'url');
    }

    /**
     * Render the breadcrumb and returns it's html
     *
     * @return string
     */
    public function render(): string
    {
        if (empty($this->items)) {
            return "";
        }

        return view(config('breadcrumb.view'), [
            'items' => $this->items
        ])->render();
    }

    /**
     * Static function to create a single breadcrumb
     *
     * @param string $title
     * @param string $url
     * @param boolean $isRoute
     * @return void
     */
    public static function push(string $title, string $url = "", bool $isRoute = true)
    {
        return self::pushArray([[$title, $url, $isRoute]]);
    }

    /**
     * Static function to create a breadcrumb with multiple items
     *
     * @param array $pushList
     * @return void
     */
    public static function pushArray(array $pushList)
    {
        foreach ($pushList as $item) {
            $title = $item[0];
            $url = $item[1] ?? "";
            $isRoute = $item[2] ?? true;
            self::getInstance()->add($title, $url, $isRoute);
        }
    }

    /**
     * Retorna o html do breadcrumb renderizado
     *
     * @return void
     */
    public static function getHtml()
    {
        return self::getInstance()->render();
    }

}
