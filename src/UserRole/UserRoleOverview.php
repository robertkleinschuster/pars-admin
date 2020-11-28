<?php


namespace Pars\Admin\UserRole;


use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Role\RoleOverview;

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
}
