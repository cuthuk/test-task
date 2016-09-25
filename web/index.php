<?php
/**
 * Created by PhpStorm.
 * User: sitnikov
 */
require_once('../Task/bootstrap.php');

$config = \Task\Config::getInstance();
$router = new \Task\Router();
$actionParams =  $router->getActionParams();
$controllerName = '\Task\Controller\\'.$actionParams['controller'];
$controller = new $controllerName();
echo call_user_func_array(array($controller, $actionParams['action']), array());