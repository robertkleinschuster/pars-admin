<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keeps the global namespace clean.
 */
(function () {

    /** @var \Psr\Container\ContainerInterface $container */
    $container = require __DIR__ . '/../config/container.php';
    $adapter = $container->get(\Laminas\Db\Adapter\AdapterInterface::class);

    $schemaUpdater = new \Pars\Core\Database\Updater\SpecialUpdater($adapter);
    $schemaUpdater->executeSilent();
    echo 'Special Updated';
})();
