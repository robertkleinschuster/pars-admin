<?php


namespace Pars\Admin\User;


use Pars\Admin\Base\BaseDelete;

class UserDelete extends BaseDelete
{
    protected function initialize()
    {
        parent::initialize();
    }
    protected function getRedirectController(): string
    {
        return 'user';
    }

    protected function getRedirectAction(): string
    {
        return 'index';
    }

    protected function getRedirectIdFields(): array
    {
        return [];
    }

}
