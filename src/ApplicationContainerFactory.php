<?php

declare(strict_types=1);

namespace Pars\Admin;


use Pars\Core\Application\AbstractApplicationContainer;
use Pars\Core\Application\AbstractApplicationContainerFactory;

/**
 * Class ApplicationContainerFactory
 * @package Pars\Admin
 */
class ApplicationContainerFactory extends AbstractApplicationContainerFactory
{
    protected function initApplicationConfigProvider()
    {
        parent::initApplicationConfigProvider();
        $this->addConfigProvider(\Mezzio\Plates\ConfigProvider::class);
        $this->addConfigProvider(\Pars\Mvc\ConfigProvider::class);
        $this->addConfigProvider(\Pars\Component\ConfigProvider::class);
        $this->addConfigProvider(\Pars\Model\ConfigProvider::class);
        $this->addConfigProvider( \Pars\Admin\ConfigProvider::class);
    }

    protected function createApplicationContainer(array $dependencies): AbstractApplicationContainer
    {
        return new ApplicationContainer($dependencies);
    }

}
