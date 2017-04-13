<?php
/**
 * Created by PhpStorm.
 * User: cuthuk
 * Date: 24.09.2016
 * Time: 12:52
 */

namespace Task\View;


interface ViewInterface
{
    public function getTemplatesPath();
    public function getCachePath();
    public function render($template, $params);
}