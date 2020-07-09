<?php


class Router
{
    private $routes;

    public function __construct()
    {
        $routesPath = ROOT . '/config/routes.php';
        $this->routes = include($routesPath);
    }

    /**
     * get  request uri
     * @return string
     */
    private function getURI()
    {
        if (!empty ($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run()
    {
        $uri = $this->getURI();

        // check existence of such request in routes.php
        foreach ($this->routes as $uriPattern => $path) {

            //compare $uriPattern with $uri
            if (preg_match("~^$uriPattern~", $uri)) {

                // получаем внутренний путь из внешнего согласно правилу
                $internalRoute = preg_replace("~^$uriPattern~", $path, $uri);

                $segments = explode('/', $internalRoute);
                // echo " segments = ".$segments;

                $controllerName = ucfirst(array_shift($segments) . 'Controller');

                $actionName = 'action' . ucfirst(array_shift($segments));

                $parameters = $segments;


                // include file of controller
                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }

                //create object, call method (action)
                $controllerObject = new $controllerName;

                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
                if ($result != null) {
                    break;
                }
            }
        }
    }

}