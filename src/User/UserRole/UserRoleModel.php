<?php

namespace Pars\Admin\User\UserRole;

use Exception;
use Pars\Admin\User\Role\RoleModel;
use Pars\Bean\Type\Base\BeanInterface;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Authorization\Role\RoleBean;
use Pars\Model\Authorization\Role\RoleBeanFinder;
use Pars\Model\Authorization\Role\RoleBeanList;
use Pars\Model\Authorization\UserRole\UserRoleBeanFinder;
use Pars\Model\Authorization\UserRole\UserRoleBeanProcessor;

/**
 * Class UserRoleModel
 * @package Pars\Admin\UserRole
 * @method UserRoleBeanFinder getBeanFinder()
 * @method UserRoleBeanProcessor getBeanProcessor()
 */
class UserRoleModel extends RoleModel
{
    /**
     *
     */
    public function initialize()
    {
        $this->setBeanFinder(new UserRoleBeanFinder($this->getDatabaseAdapter()));
        $this->setBeanProcessor(new UserRoleBeanProcessor($this->getDatabaseAdapter()));
    }

    /**
     * @param array $userPermissions
     * @param IdParameter $idParameter
     * @return RoleBeanList
     * @throws Exception
     */
    public function getRoleBeanList(array $userPermissions, IdParameter $idParameter): RoleBeanList
    {
        $finder = new UserRoleBeanFinder($this->getDatabaseAdapter());
        $finder->getBeanLoader()->initByIdMap($idParameter->getAttribute_List());
        $beanList = $finder->getBeanList();
        $existing = $beanList->column('UserRole_Code');
        $finder = new RoleBeanFinder($this->getDatabaseAdapter());
        $finder->setUserRole_Active(true);
        return $finder->getBeanList()->filter(
            function (BeanInterface $bean) use ($userPermissions, $existing) {
                if ($bean instanceof RoleBean) {
                    $permissions = $bean->UserPermission_BeanList->column('UserPermission_Code');
                    return empty(array_diff($permissions, $userPermissions))
                        && !in_array($bean->UserRole_Code, $existing);
                }
                return false;
            }
        );
    }
}
