<?php

declare(strict_types=1);

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
            // Fully\Qualified\ClassOrInterfaceName::class => Fully\Qualified\ClassName::class,
            Mezzio\Application::class => Pars\Admin\Application::class,
            Mezzio\Container\ApplicationFactory::class => Pars\Admin\ApplicationFactory::class,
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories' => [
            // Fully\Qualified\ClassName::class => Fully\Qualified\FactoryName::class,
            Pars\Admin\Application::class => Pars\Admin\ApplicationFactory::class,
            Pars\Admin\ApplicationContainer::class => Pars\Admin\ApplicationContainerFactory::class,
            EmitterInterface::class => \Pars\Core\Emitter\EmitterFactory::class,
        ],
    ],
];
