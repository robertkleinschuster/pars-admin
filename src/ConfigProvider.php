<?php

declare(strict_types=1);

namespace Pars\Admin;

/**
 * The configuration provider for the Admin module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [

            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                \Pars\Admin\Application::class => \Pars\Admin\ApplicationFactory::class,
                \Pars\Admin\ApplicationContainer::class => \Pars\Admin\ApplicationContainerFactory::class,
            ],
        ];
    }
}
