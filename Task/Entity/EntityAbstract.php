<?php

namespace Task\Entity;


class EntityAbstract
{
    public function __construct($data = null)
    {
        if ($data) {
            foreach ($data as $column => $value) {
                $method = explode('_', $column);
                if(count($method)) {
                    foreach($method as &$part) {
                        $part = ucfirst($part);
                    }
                }
                $method = 'set'.implode('', $method);
                call_user_func_array(array($this, $method), array($column => $value));
            }
        }
    }

    public static function getTable()
    {
        return '';
    }

}