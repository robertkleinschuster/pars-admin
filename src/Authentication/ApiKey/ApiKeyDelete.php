<?php


namespace Pars\Admin\Authentication\ApiKey;


use Pars\Admin\Base\BaseDelete;

class ApiKeyDelete extends BaseDelete
{
    protected function getRedirectController(): string
    {
        return 'apikey';
    }

}
