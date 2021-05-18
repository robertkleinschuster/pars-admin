<?php

namespace Pars\Admin\Setup;

use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Authorization\Permission\PermissionBeanFinder;
use Pars\Model\Authorization\Role\RoleBeanFinder;
use Pars\Model\Authorization\Role\RoleBeanProcessor;
use Pars\Model\Authorization\RolePermission\RolePermissionBeanFinder;
use Pars\Model\Authorization\RolePermission\RolePermissionBeanProcessor;
use Pars\Model\Authorization\UserRole\UserRoleBeanFinder;
use Pars\Model\Authorization\UserRole\UserRoleBeanProcessor;
use Pars\Model\Updater\Database\DataDatabaseUpdater;
use Pars\Model\Updater\Database\SchemaDatabaseUpdater;
use Pars\Pattern\Exception\CoreException;

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

    protected function create(IdParameter $idParameter, array &$attributes): void
    {
        try {
            $this->getParsContainer()->getDatabaseAdapter()->startTransaction();
            $schemaUpdater = new SchemaDatabaseUpdater($this->getDbAdpater());
            $schemaUpdater->executeSilent();
            $dataUpdater = new DataDatabaseUpdater($this->getDbAdpater());
            $dataUpdater->executeSilent();
            parent::create($idParameter, $attributes);
            if ($this->getBeanFinder()->count() == 1) {
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
                    $this->getParsContainer()->getDatabaseAdapter()->commitTransaction();
                } else {
                    throw new CoreException('Could not create user.');
                }
            } else {
                throw new CoreException('Could not create user.');
            }
        } catch (\Throwable $exception) {
            $this->getParsContainer()->getDatabaseAdapter()->rollbackTransaction();
            $this->getLogger()->error($exception->getMessage(), ['exception' => $exception]);
            $this->getValidationHelper()->addError('error', $exception->getMessage());
            $this->getValidationHelper()->addError('errorDetails', $exception->getTraceAsString());
        }
    }

}
