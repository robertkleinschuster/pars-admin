<?php

/**
 * @see       https://github.com/mezzio/mezzio-skeleton for the canonical source repository
 * @copyright https://github.com/mezzio/mezzio-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/mezzio/mezzio-skeleton/blob/master/LICENSE.md New BSD License
 */


declare(strict_types=1);


require 'vendor/autoload.php';

if (file_exists('data/cache/config/config.php')) {
    unlink('data/cache/config/config.php');
}

$command = new \Pars\Cli\DevCommand();
echo($command(array_slice($argv, 1), __DIR__ . '/../'));
exit;