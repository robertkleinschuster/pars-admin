<?php

namespace Pars\Admin\User\UserRole;

use Pars\Admin\User\Role\RoleDelete;

class UserRoleDelete extends RoleDelete
{
    protected function getRedirectController(): string
    {
        return 'user';
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

    protected function getRedirectIdFields(): array
    {
        return [
            'Person_ID'
        ];
    }
}
