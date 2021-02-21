<?php


namespace Pars\Admin\Config;


use Pars\Admin\Base\BaseDelete;

/**
 * Class ConfigDelete
 * @package Pars\Admin\Config
 */
class ConfigDelete extends BaseDelete
{
    protected function getRedirectController(): string
    {
        return 'config';
    }

}
