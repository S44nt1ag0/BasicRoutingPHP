<?php
namespace SimpleApi;

class Router
{
    private $routes = array();

    public function create(string $method, string $path, callable $callback)
    {
        $this->routes[$method][$path] = $callback;
    }

    public function init()
    {
        header('Content-Type: application/json; charset=utf-8');

        $httpMethod = $_SERVER["REQUEST_METHOD"];

        if (isset($this->routes[$httpMethod])) {
            foreach (
                $this->routes[$httpMethod] as $path => $callback
            ) {
                if ($path === $_SERVER["REQUEST_URI"]) {
                    return $callback();
                }
            }
        }

        http_response_code(404);
        return;

    }


}

?>