<?php

namespace Pars\Admin\RolePermission;

use Pars\Admin\Base\CrudModel;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Authorization\Permission\PermissionBean;
use Pars\Model\Authorization\Permission\PermissionBeanFinder;
use Pars\Model\Authorization\Permission\PermissionBeanList;
use Pars\Model\Authorization\RolePermission\RolePermissionBeanFinder;
use Pars\Model\Authorization\RolePermission\RolePermissionBeanProcessor;

class RolePermissionModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new RolePermissionBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new RolePermissionBeanProcessor($this->getDbAdpater()));
    }


    public function getPermissionBeanList(array $userPermissions, IdParameter $idParameter): PermissionBeanList
    {
        $finder = new RolePermissionBeanFinder($this->getDbAdpater());
        $finder->getBeanLoader()->initByIdMap($idParameter->getAttribute_List());

        $beanList = $finder->getBeanList();
        $existing = $beanList->column('UserPermission_Code');
        $finder = new PermissionBeanFinder($this->getDbAdpater());
        $finder->setUserPermission_Active(true);
        return $finder->getBeanList()->filter(
            function (PermissionBean $bean) use ($existing, $userPermissions) {
                return !in_array($bean->UserPermission_Code, $existing)
                    && in_array($bean->UserPermission_Code, $userPermissions);
            }
        );
    }
}
