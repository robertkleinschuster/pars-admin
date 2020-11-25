<?php

namespace Pars\Admin\RolePermission;

use Pars\Admin\Base\CrudModel;
use Pars\Model\Authorization\Permission\PermissionBeanFinder;
use Pars\Model\Authorization\RolePermission\RolePermissionBeanFinder;
use Pars\Model\Authorization\RolePermission\RolePermissionBeanProcessor;
use Pars\Helper\Parameter\IdParameter;

class RolePermissionModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new RolePermissionBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new RolePermissionBeanProcessor($this->getDbAdpater()));
    }


    public function getPermissionList(array $userPermissions, IdParameter $idParameter): array
    {
        $finder = new RolePermissionBeanFinder($this->getDbAdpater());
        $finder->getBeanLoader()->initByIdMap($idParameter->getAttribute_List());

        $beanList = $finder->getBeanList();
        $existing = $beanList->getData('UserPermission_Code');
        $finder = new PermissionBeanFinder($this->getDbAdpater());
        $finder->setUserPermission_Active(true);

        $permissionList = [];
        $beanList = $finder->getBeanList();
        foreach ($beanList as $item) {
            $code = $item->getData('UserPermission_Code');
            if (!in_array($code, $existing) && in_array($code, $userPermissions)) {
                $permissionList[$code] = $code;
            }
        }
        return $permissionList;
    }
}
