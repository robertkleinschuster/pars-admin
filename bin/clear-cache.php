<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keeps the global namespace clean.
 */
(function () {

    /** @var \Psr\Container\ContainerInterface $container */
    $container = require __DIR__ . '/../config/container.php';
    $cache = $container->get(\Pars\Core\Deployment\CacheClearer::class);
    $now = new DateTime();
    if ($now->format('H:i') != '00:00') {
        $cache->removeOption(\Pars\Core\Deployment\CacheClearer::OPTION_CLEAR_BUNDLES);
        $cache->removeOption(\Pars\Core\Deployment\CacheClearer::OPTION_CLEAR_CONFIG);
        $cache->removeOption(\Pars\Core\Deployment\CacheClearer::OPTION_RESET_OPCACHE);
        $cache->removeOption(\Pars\Core\Deployment\CacheClearer::OPTION_CLEAR_CACHE_POOL);
    }
    $cache->removeOption(\Pars\Core\Deployment\CacheClearer::OPTION_CLEAR_IMAGES);
    $cache->removeOption(\Pars\Core\Deployment\CacheClearer::OPTION_CLEAR_ASSETS);
    $cache->clear();
    echo 'Cache Cleared';
})();
