<?php

namespace Pars\Admin\UserRole;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Role\RoleModel;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Authorization\Role\RoleBean;
use Pars\Model\Authorization\Role\RoleBeanFinder;
use Pars\Model\Authorization\Role\RoleBeanList;
use Pars\Model\Authorization\UserRole\UserRoleBeanFinder;
use Pars\Model\Authorization\UserRole\UserRoleBeanProcessor;

class UserRoleModel extends RoleModel
{

    public function initialize()
    {
        $this->setBeanFinder(new UserRoleBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new UserRoleBeanProcessor($this->getDbAdpater()));
    }


    public function getRoleBeanList(array $userPermissions, IdParameter $idParameter): RoleBeanList
    {
        $finder = new UserRoleBeanFinder($this->getDbAdpater());
        $finder->getBeanLoader()->initByIdMap($idParameter->getAttribute_List());
        $beanList = $finder->getBeanList();
        $existing = $beanList->column('UserRole_Code');
        $finder = new RoleBeanFinder($this->getDbAdpater());
        $finder->setUserRole_Active(true);
        return $finder->getBeanListDecorator()->filter(
            function (BeanInterface $bean) use ($userPermissions, $existing) {
                if ($bean instanceof RoleBean) {
                    $permissions = $bean->UserPermission_BeanList->column('UserPermission_Code');
                    return empty(array_diff($permissions, $userPermissions))
                        && !in_array($bean->UserRole_Code, $existing);
                }
            });
    }
}
