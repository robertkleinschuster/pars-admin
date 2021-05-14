<?php

declare(strict_types=1);

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    if (file_exists('public' . $_SERVER['SCRIPT_NAME']) && count($_GET) == 0) {
        return false;
    }
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';
require 'version.php';
/**
 * Self-called anonymous function that creates its own scope and keeps the global namespace clean.
 */
(function () {
    require 'vendor/pars/pars-core/configure_db.php';

    /** @var \Psr\Container\ContainerInterface $container */
    $container = require 'config/container.php';

    /** @var \Mezzio\Application $app */
    $app = $container->get(\Pars\Admin\Application::class);
    $app->run();
})();
