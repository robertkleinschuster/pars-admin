<?php


namespace Pars\Admin\UserRole;


use Pars\Admin\Role\RoleDelete;

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
