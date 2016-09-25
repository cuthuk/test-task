<?php
session_save_path(__DIR__ . "/../cache/session/");
session_start();

$loader = require __DIR__ . '/../vendor/autoload.php';
spl_autoload_register(array($loader, 'loadClass'));

require_once('autoload.php');
$taskLoader = new AutoLoader();
$taskLoader->addNamespace('Task',__DIR__);
$taskLoader->register();
