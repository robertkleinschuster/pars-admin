<?php

namespace Pars\Admin\Setup;

use Pars\Core\Database\Updater\DataUpdater;
use Pars\Core\Database\Updater\SchemaUpdater;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Authorization\Permission\PermissionBeanFinder;
use Pars\Model\Authorization\Role\RoleBeanFinder;
use Pars\Model\Authorization\Role\RoleBeanProcessor;
use Pars\Model\Authorization\RolePermission\RolePermissionBeanFinder;
use Pars\Model\Authorization\RolePermission\RolePermissionBeanProcessor;
use Pars\Model\Authorization\UserRole\UserRoleBeanFinder;
use Pars\Model\Authorization\UserRole\UserRoleBeanProcessor;

/**
 * Class SetupModel
 * @package Pars\Admin\Setup
 */
class SetupModel extends \Pars\Admin\Base\BaseModel
{
    public function initialize()
    {
        $this->setBeanProcessor(new \Pars\Model\Authentication\User\UserBeanProcessor($this->getDbAdpater()));
        $this->setBeanFinder(new \Pars\Model\Authentication\User\UserBeanFinder($this->getDbAdpater()));
    }

    protected function create(IdParameter $idParameter, array $attributes): void
    {

        $schemaUpdater = new SchemaUpdater($this->getDbAdpater());
        $methods = [];
        foreach ($schemaUpdater->getUpdateMethodList() as $method) {
            $methods[$method] = true;
        }
        $result = $schemaUpdater->execute($methods);

        $dataUpdater = new DataUpdater($this->getDbAdpater());
        $methods = [];
        foreach ($dataUpdater->getUpdateMethodList() as $method) {
            $methods[$method] = true;
        }
        $result = $dataUpdater->execute($methods);
        $this->getDbAdpater()->getDriver()->getConnection()->beginTransaction();
        parent::create($idParameter, $attributes);
        if ($this->getBeanFinder()->count() == 1) {
            try {
                $user = $this->getBeanFinder()->getBean();
                $roleFinder = new RoleBeanFinder($this->getDbAdpater());
                $role = $roleFinder->getBeanFactory()->getEmptyBean([]);
                $role->set('UserRole_Code', 'admin');
                $role->set('UserRole_Name', 'Administrator');
                $role->set('UserRole_Active', true);
                $roleList = $roleFinder->getBeanFactory()->getEmptyBeanList();
                $roleList->push($role);

                $roleProcessor = new RoleBeanProcessor($this->getDbAdpater());
                $roleProcessor->setBeanList($roleList);
                $roleProcessor->save();

                if ($roleFinder->count() == 1) {
                    $role = $roleFinder->getBean();
                    $permissionFinder = new PermissionBeanFinder($this->getDbAdpater());
                    $permissionBeanList = $permissionFinder->getBeanList();

                    $rolePermissionFinder = new RolePermissionBeanFinder($this->getDbAdpater());
                    $rolePermissionBeanList = $rolePermissionFinder->getBeanFactory()->getEmptyBeanList();

                    foreach ($permissionBeanList as $permission) {
                        $rolePermission = $rolePermissionFinder->getBeanFactory()->getEmptyBean([]);
                        $rolePermission->set('UserRole_ID', $role->get('UserRole_ID'));
                        $rolePermission->set('UserPermission_Code', $permission->get('UserPermission_Code'));
                        $rolePermissionBeanList->push($rolePermission);
                    }

                    $rolePermissionProcessor = new RolePermissionBeanProcessor($this->getDbAdpater());
                    $rolePermissionProcessor->setBeanList($rolePermissionBeanList);
                    $rolePermissionProcessor->save();

                    $userRoleFinder = new UserRoleBeanFinder($this->getDbAdpater());
                    $userRole = $userRoleFinder->getBeanFactory()->getEmptyBean([]);
                    $userRoleList = $userRoleFinder->getBeanFactory()->getEmptyBeanList();
                    $userRole->set('Person_ID', $user->get('Person_ID'));
                    $userRole->set('UserRole_ID', $role->get('UserRole_ID'));
                    $userRoleList->push($userRole);

                    $userRoleProcessor = new UserRoleBeanProcessor($this->getDbAdpater());
                    $userRoleProcessor->setBeanList($userRoleList);
                    $userRoleProcessor->save();
                    $this->getDbAdpater()->getDriver()->getConnection()->commit();
                } else {
                    $this->getDbAdpater()->getDriver()->getConnection()->rollback();
                }
            } catch (\Throwable $exception) {
                $this->getDbAdpater()->getDriver()->getConnection()->rollback();
                $this->getValidationHelper()->addError('error', $exception->getMessage());
                $this->getValidationHelper()->addError('errorDetails', $exception->getTraceAsString());
            }
        } else {
            $this->getDbAdpater()->getDriver()->getConnection()->rollback();
        }
    }
}
