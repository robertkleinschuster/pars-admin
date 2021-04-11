<?php

namespace Pars\Admin;

use Laminas\ServiceManager\ServiceManager;
use Pars\Core\Deployment\CacheClearer;

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

    public function get($name)
    {
        try {
            return parent::get($name);
        } catch (\Exception $exception) {
            $config = [];
            if ($this->has('config')) {
                $config = $this->get('config');
            }
            CacheClearer::clearConfigCache($config);
            throw $exception;
        }
    }

}
