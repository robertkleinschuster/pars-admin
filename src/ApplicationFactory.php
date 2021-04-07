<?php

namespace Pars\Admin;

use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Mezzio\Router\RouteCollector;
use Pars\Core\Deployment\UpdateHandler;
use Psr\Container\ContainerInterface;

class ApplicationFactory extends \Mezzio\Container\ApplicationFactory
{
    public function __invoke(ContainerInterface $container): Application
    {
        $app = new Application(
            $container->get(MiddlewareFactory::class),
            $container->get('Mezzio\ApplicationPipeline'),
            $container->get(RouteCollector::class),
            $container->get(RequestHandlerRunner::class)
        );
        register_shutdown_function(function () use ($container) {
            $error = error_get_last();
            if (isset($error['type']) && $error['type'] === E_ERROR) {
                UpdateHandler::handleAppUpdate($container);
            }
        });
        $factory = $container->get(\Mezzio\MiddlewareFactory::class);
        (require realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'config', 'pipeline.php'])))($app, $factory, $container);
        (require realpath(implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'config', 'routes.php'])))($app, $factory, $container);
        return $app;
    }
}
