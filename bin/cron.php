<?php
declare(strict_types=1);
chdir(dirname(__DIR__));
require 'vendor/pars/pars-core/initialize.php';

/**
 * Self-called anonymous function that creates its own scope and keeps the global namespace clean.
 */
(function () {
    /** @var \Psr\Container\ContainerInterface $container */
    $container = require PARS_CONTAINER;
    /**
     * @var $logger \Psr\Log\LoggerInterface
     */
    $logger = $container->get(\Psr\Log\LoggerInterface::class);
    $config = $container->get(\Pars\Core\Config\ParsConfig::class);
    $timezone = $config->get('admin.timezone');
    $now = new DateTime('now', new DateTimeZone($timezone));
    $tasklogFinder = new \Pars\Model\Task\Log\TaskLogBeanFinder($container->get(\Pars\Core\Database\ParsDatabaseAdapter::class));
    $tasklogProcessor = new \Pars\Model\Task\Log\TaskLogBeanProcessor($container->get(\Pars\Core\Database\ParsDatabaseAdapter::class));
    $tasklogBeanList = $tasklogFinder->getBeanFactory()->getEmptyBeanList();
    $config = $container->get('config');
    if (isset($config['task'])) {
        foreach ($config['task'] as $class => $taskConfig) {
            $tasklogBean = $tasklogFinder->getBeanFactory()->getEmptyBean([]);
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
                    $tasklogBean->set('TaskLog_Message', 'Task error: ' . $class . ' Message: ' . $exception->getMessage());
                    $tasklogBean->set('TaskLog_Text', $exception->__toString());
                    $tasklogBeanList->push($tasklogBean);
                }
            } else {
                $logger->error('Task error: ' . $class . ' Config: ' . print_r($taskConfig, true));
                $tasklogBean->set('TaskLog_Message', 'Task config error: ' . $class);
                $tasklogBean->set('TaskLog_Text', print_r($taskConfig, true));
                $tasklogBeanList->push($tasklogBean);
            }
        }
        $tasklogProcessor->setBeanList($tasklogBeanList)->save();
    }
})();
