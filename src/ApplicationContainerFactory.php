<?php

declare(strict_types=1);

namespace Pars\Admin;


use Pars\Core\Application\AbstractApplicationContainer;
use Pars\Core\Application\AbstractApplicationContainerFactory;

class ApplicationContainerFactory extends AbstractApplicationContainerFactory
{

    protected function getApplicationConfig(): array
    {
        return require realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'config', 'config.php']));
    }

    protected function createApplicationContainer(array $dependencies): AbstractApplicationContainer
    {
        return new ApplicationContainer($dependencies);
    }


}
