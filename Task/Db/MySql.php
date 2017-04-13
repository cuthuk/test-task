<?php
/**
 * Created by PhpStorm.
 * User: cuthuk
 * Date: 24.09.2016
 * Time: 13:17
 */

namespace Task\Db;


class MySql
{
    const PLACEHOLDER_PREFIX = ":";
    const SQL_DATETIME = 'Y-m-d h:i:s';
    private $_connection;
    private static $_instance;

    public static function getInstance()
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getConnection()
    {
        return $this->_connection;
    }

    private function __construct()
    {
        $dbsettings = \Task\Config::getInstance()->db;
        $this->_connection = new \PDO(
            "mysql:host={$dbsettings['host']};dbname={$dbsettings['db']}",
            $dbsettings['login'], $dbsettings['password'],
            array(\PDO::ATTR_PERSISTENT => true)
        );
        $this->_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    private function __clone()
    {

    }

    public function query($sql) {
        return $this->_connection->query($sql);
    }
}