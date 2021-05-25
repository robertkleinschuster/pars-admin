<?php
declare(strict_types=1);
chdir(dirname(__DIR__));
include 'vendor/pars/pars-core/initialize.php';
$container = require PARS_CONTAINER;
$container->get(\Pars\Core\Deployment\CacheClearer::class)->clear();
