<?php

namespace Pars\Admin;

use Laminas\ServiceManager\ServiceManager;

class ApplicationContainer extends ServiceManager
{
    public static ?self $instance = null;

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = (new ApplicationContainerFactory())();
        }
        return self::$instance;
    }
}
