<?php

namespace Pars\Admin\User\UserRole;

use Pars\Admin\User\Role\RoleOverview;

class UserRoleOverview extends RoleOverview
{
    protected function getController(): string
    {
        return 'userrole';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'UserRole_ID', 'Person_ID'
        ];
    }

    protected function getCreateIdFields(): array
    {
        return [
            'Person_ID'
        ];
    }

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
