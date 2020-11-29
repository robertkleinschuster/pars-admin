<?php


namespace Pars\Admin\Config;


use Pars\Admin\Base\BaseDelete;

class ConfigDelete extends BaseDelete
{
    protected function getRedirectController(): string
    {
        return 'config';
    }

}
