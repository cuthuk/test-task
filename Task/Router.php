<?php


namespace Task;


class Router
{
    private $routes;

    private $_controller = 'index';
    private $_action = 'index';

    function __construct()
    {
        // Получаем роуты из файла.
        $this->routes = include(__DIR__.'/settings/routes.php');
    }

    function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    function getActionParams()
    {
        // Получаем URI.
        $uri = $this->getURI();
        // Пытаемся применить к нему правила из конфигуации.

        foreach ($this->routes as $pattern => $route) {
            if (preg_match("~$pattern~", $uri)) {
                // Получаем внутренний путь из внешнего согласно правилу.
                $internalRoute = preg_replace("~$pattern~", $route, $uri);
                // Разбиваем внутренний путь на сегменты.
                $segments = explode('/', $internalRoute);
                if (count($segments)) {
                    // Первый сегмент — контроллер.
                    $this->_controller = array_shift($segments);
                    // Второй — действие.
                    $this->_action = array_shift($segments);
                    break;
                }
            }
        }

        return array(
            'controller' => ucfirst($this->_controller) . 'Controller',
            'action' => ucfirst($this->_action) . 'Action',
        );
    }
}