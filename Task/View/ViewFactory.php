<?php

namespace Task\View;



class ViewFactory
{
    public static function getView($engine = 'Twig')
    {
        switch($engine) {
            default:
                return new Twig();
        }
    }

}