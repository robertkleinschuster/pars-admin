<?php

namespace Pars\Admin;

use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\Stratigility\MiddlewarePipeInterface;
use Mezzio\MiddlewareFactory;
use Mezzio\Router\RouteCollector;
use Pars\Core\Application\AbstractApplication;
use Pars\Core\Application\AbstractApplicationContainer;
use Pars\Core\Application\AbstractApplicationFactory;
use Pars\Core\Authentication\AuthenticationMiddleware;

class ApplicationFactory extends AbstractApplicationFactory
{
    protected function createApplication(
        MiddlewareFactory $factory,
        MiddlewarePipeInterface $pipeline,
        RouteCollector $routes,
        RequestHandlerRunner $runner
    ): AbstractApplication{
        return new \Pars\Admin\Application($factory, $pipeline, $routes, $runner);
    }

    protected function initPipeline(AbstractApplication $app, MiddlewareFactory $factory, AbstractApplicationContainer $container)
    {
        parent::initPipeline($app, $factory, $container);
        $app->pipe(AuthenticationMiddleware::class);
    }


    protected function initRoutes(
        AbstractApplication $app,
        MiddlewareFactory $factory,
        AbstractApplicationContainer $container
    )
    {
        $app->any(\Pars\Mvc\Handler\MvcHandler::getRoute(), \Pars\Mvc\Handler\MvcHandler::class, 'mvc');
    }


}
