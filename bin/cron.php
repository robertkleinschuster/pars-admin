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
    $adapter = $container->get(\Laminas\Db\Adapter\AdapterInterface::class);

    $finder = new \Pars\Model\Import\ImportBeanFinder($adapter);
    $timezone = (new \Pars\Model\Config\ConfigBeanFinder($adapter))
        ->setConfig_Code('admin.timezone')
        ->getBean()
        ->get('Config_Value');
    $now = new DateTime('now', new DateTimeZone($timezone));
    foreach ($finder->getBeanListDecorator() as $bean) {
        switch ($bean->get('ImportType_Code')) {
            case 'tesla':
                $importer = new \Pars\Import\Tesla\TeslaImporter($bean);
                if ($importer->isAllowed($now)) {
                    $importer->run();
                }
                $processor = new \Pars\Model\Import\ImportBeanProcessor($adapter);
                $beanList = $finder->getBeanFactory()->getEmptyBeanList()->push($importer->getBean());
                $processor->setBeanList($beanList)->save();
                break;
        }
    }
})();
