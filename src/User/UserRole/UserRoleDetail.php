<?php

namespace Pars\Admin\User\UserRole;

use Pars\Admin\User\Role\RoleDetail;

class UserRoleDetail extends RoleDetail
{
    protected function getIndexController(): string
    {
        return 'user';
    }

    protected function getEditController(): string
    {
        return 'userrole';
    }


    protected function getIndexAction(): string
    {
        return 'detail';
    }

    protected function getIndexIdFields(): array
    {
        return [
            'Person_ID'
        ];
    }

    protected function getEditAction(): string
    {
        return 'edit';
    }

    protected function getEditIdFields(): array
    {
        return ['UserRole_ID', 'Person_ID'];
    }
}
