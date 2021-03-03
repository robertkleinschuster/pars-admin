<?php

namespace Pars\Admin\Role;

use Pars\Admin\Base\BaseDelete;

class RoleDelete extends BaseDelete
{
    protected function initialize()
    {
        parent::initialize();
    }

    protected function getRedirectController(): string
    {
        return 'role';
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
