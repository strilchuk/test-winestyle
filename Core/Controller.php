<?php
/**
 *  File: Controller.php
 *  Author: Alexander Strilchuk <strilchukalexander@gmail.com>
 *  Date: 2020-5-15
 *  Copyright Strilchuk (c) 2020
 */


namespace Core;

/**
 * Base controller
 *
 */
abstract class Controller
{

    /**
     * @param string $name Method name
     * @param array $args Arguments passed to the method
     *
     * @return void
     */
    public function __call($name, $args)
    {
        $method = explode("Action", $name)[0];

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    /**
     * @return void
     */
    protected function before()
    {
    }

    /**
     * @return void
     */
    protected function after()
    {
    }
}
