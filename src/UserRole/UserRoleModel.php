<?php

namespace Pars\Admin\UserRole;

use Pars\Admin\Base\CrudModel;
use Pars\Model\Authorization\Role\RoleBeanFinder;
use Pars\Model\Authorization\UserRole\UserRoleBeanFinder;
use Pars\Model\Authorization\UserRole\UserRoleBeanProcessor;
use Pars\Helper\Parameter\IdParameter;

class UserRoleModel extends CrudModel
{

    public function initialize()
    {
        $this->setBeanFinder(new UserRoleBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new UserRoleBeanProcessor($this->getDbAdpater()));
    }


    public function getRoleList(array $userPermissions, IdParameter $idParameter): array
    {
        $finder = new UserRoleBeanFinder($this->getDbAdpater());
        $finder->getBeanLoader()->initByIdMap($idParameter->getAttribute_List());

        $beanList = $finder->getBeanList();
        $existing = $beanList->getData('UserRole_Code');
        $finder = new RoleBeanFinder($this->getDbAdpater());
        $finder->setUserRole_Active(true);

        $RoleList = [];
        foreach ($finder->getBeanListDecorator() as $item) {
            $id = $item->getData('UserRole_ID');
            $code = $item->getData('UserRole_Code');
            $permissions = $item->getData('UserPermission_BeanList')->getData('UserPermission_Code');
            $allowed = true;
            foreach ($permissions as $permission) {
                if (!in_array($permission, $userPermissions)) {
                    $allowed = false;
                    break;
                }
            }
            if (!in_array($code, $existing) && $allowed) {
                $RoleList[$id] = $code;
            }
        }
        return $RoleList;
    }
}
