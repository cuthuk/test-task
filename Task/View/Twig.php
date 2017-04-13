<?php
/**
 * Created by PhpStorm.
 * User: cuthuk
 * Date: 24.09.2016
 * Time: 14:19
 */

namespace Task\View;

use Task\Config;

use Twig_Loader_Filesystem;
use Twig_Environment;

class Twig  implements ViewInterface
{
    public function getTemplatesPath()
    {
        $config = Config::getInstance();
        return $_SERVER['DOCUMENT_ROOT']."/../Task/Template/".$config->templates['path'];
    }

    public function getCachePath()
    {
        $config = Config::getInstance();
        return $_SERVER['DOCUMENT_ROOT']."/../cache/Template/".$config->templates['cache'];
    }

    public function render($template, $params = array())
    {
        $loader = new Twig_Loader_Filesystem($this->getTemplatesPath() );

        $twig = new Twig_Environment($loader, array(
            'cache' => $this->getCachePath(),
        ));

        return $twig->render($template, $params);
    }

}