<?php
declare(strict_types=1);

namespace Pars\Admin;

use Psr\Container\ContainerInterface;

class ApplicationContainerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $this->getApplicationConfig();
        $dependencies = $config['dependencies'];
        $dependencies['services']['config'] = $config;
        return new ApplicationContainer($dependencies);
    }

    protected function getApplicationConfig(): array
    {
        return require realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'config', 'config.php']));
    }
}
