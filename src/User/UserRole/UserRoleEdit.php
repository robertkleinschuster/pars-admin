<?php

namespace Pars\Admin\User\UserRole;

use Pars\Admin\User\Role\RoleEdit;

/**
 * Class UserRoleEdit
 * @package Pars\Admin\UserRole
 */
class UserRoleEdit extends RoleEdit
{
    /**
     *
     */
    protected function initFields()
    {
        $this->setCreateBulk(true);
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

    protected function getCreateRedirectAction(): string
    {
        return 'detail';
    }

    protected function getCreateRedirectIdFields(): array
    {
        return ['Person_ID'];
    }
}
