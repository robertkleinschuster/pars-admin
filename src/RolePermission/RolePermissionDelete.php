<?php


namespace Pars\Admin\RolePermission;


use Pars\Admin\Base\BaseDelete;

class RolePermissionDelete extends BaseDelete
{
    protected function getRedirectController(): string
    {
        return 'role';
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

    protected function getRedirectIdFields(): array
    {
        return [
            'UserRole_ID'
        ];
    }
}
