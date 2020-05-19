<?php
/**
 *  File: Router.php
 *  Author: Alexander Strilchuk <strilchukalexander@gmail.com>
 *  Date: 2020-5-15
 *  Copyright Strilchuk (c) 2020
 */


namespace Core;

use \Klein\Klein;

/**
 * Router
 */
class Router extends Klein
{
    public function respondWithController($method, $path = '*', $controllerAction)
    {
        $handler = explode('@', $controllerAction);
        $controller = $handler[0];
        $action = $handler[1];
        $controller = $this->getNamespace() . $controller;
        if (class_exists($controller)) {
            //$callback = [new $controller($this->params),$action];
            $callback = [new $controller(), $action];
            parent::respond($method, $path, $callback);
        } else {
            throw new \Exception("Controller class $controller not found");
        }
    }

    /**
     * Get the namespace for the controller class. The namespace defined in the
     * route parameters is added if present.
     *
     * @return string The request URL
     */
    protected function getNamespace()
    {
        $namespace = 'App\Controllers\\';

        return $namespace;
    }
}
