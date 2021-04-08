<?php

namespace Pars\Admin\Base;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Model\Authentication\User\UserBean;
use Pars\Mvc\View\FieldAcceptInterface;
use Pars\Mvc\View\FieldInterface;

class UserPermissionFieldAccept implements FieldAcceptInterface
{
    protected UserBean $userBean;
    protected string $permission;

    /**
     * UserPermissionFieldAccept constructor.
     * @param UserBean $userBean
     * @param string $permission
     */
    public function __construct(UserBean $userBean, string $permission)
    {
        $this->userBean = $userBean;
        $this->permission = $permission;
    }

    public function __invoke(FieldInterface $field, ?BeanInterface $bean = null): bool
    {
        return $this->userBean->hasPermission($this->permission);
    }
}
