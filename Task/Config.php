<?php
/**
 * Created by PhpStorm.
 * User: cuthuk
 * Date: 24.09.2016
 * Time: 17:26
 */

namespace Task;


class Config
{
    protected static $instance = null;
    protected function __construct()
    {
        $params = include(__DIR__.'/settings/settings.php');
        foreach ($params as $name => $param) {
            $this->$name = $param;
        }
    }

    protected function __clone()
    {

    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) self::$instance = new Config();
        return static::$instance;
    }
}