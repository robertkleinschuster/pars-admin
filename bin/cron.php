<?php

declare(strict_types=1);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keeps the global namespace clean.
 */
(function () {
    /** @var \Psr\Container\ContainerInterface $container */
    $container = require __DIR__ . '/../config/container.php';
    /**
     * @var $logger \Psr\Log\LoggerInterface
     */
    $logger = $container->get(\Psr\Log\LoggerInterface::class);
    $config = $container->get(\Pars\Core\Config\ParsConfig::class);
    $timezone = $config->get('admin.timezone');
    $now = new DateTime('now', new DateTimeZone($timezone));
    $config = $container->get('config');
    if (isset($config['task'])) {
        foreach ($config['task'] as $class => $taskConfig) {
            if (is_array($taskConfig)) {
                try {
                    $task = new $class($taskConfig, $now, $container);
                    if ($task->isAllowed()) {
                        $logger->info('Task execute: ' . $class);
                        $task->execute();
                    }
                } catch (Throwable $exception) {
                    $logger->error(
                        'Task error: ' . $class
                        . ' Message: ' . $exception->getMessage()
                        . ' Config: ' . print_r($taskConfig, true),
                        ['exception' => $exception]
                    );
                }
            } else {
                $logger->error('Task error: ' . $class . ' Config: ' . print_r($taskConfig, true));
            }
        }
    }
})();
