<?php

namespace Task\Controller;

use Task\View\ViewFactory as Factory;
use Task\Config;

class ControllerAbstract
{
    protected $_view;

    public function getView()
    {
        return $this->_view;
    }

    public function __construct()
    {
        $config = Config::getInstance();
        $this->_view = Factory::getView($config->templates['engine']);
    }
}