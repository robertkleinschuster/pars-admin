<?php

namespace Pars\Admin\Setup;

use Pars\Admin\Base\BaseModel;
use Pars\Core\Database\Updater\AbstractDatabaseUpdater;
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
use Pars\Model\Updater\Database\SpecialDatabaseUpdater;
use Pars\Pattern\Exception\CoreException;

/**
 * Class SetupModel
 * @package Pars\Admin\Setup
 */
class SetupModel extends BaseModel
{
    public function initialize()
    {
        $this->setBeanProcessor(new \Pars\Model\Authentication\User\UserBeanProcessor($this->getDatabaseAdapter()));
        $this->setBeanFinder(new \Pars\Model\Authentication\User\UserBeanFinder($this->getDatabaseAdapter()));
        if (!$this->getDatabaseAdapter()->isValid()) {
            $schemaUpdater = new SchemaDatabaseUpdater($this->getParsContainer());
            $schemaUpdater->executeSilent();
            $dataUpdater = new DataDatabaseUpdater($this->getParsContainer());
            $dataUpdater->executeSilent();
            $dataUpdater = new SpecialDatabaseUpdater($this->getParsContainer());
            $dataUpdater->executeSilent();
        }
    }

    protected function create(IdParameter $idParameter, array &$attributes): void
    {
        try {
            $this->getParsContainer()->getDatabaseAdapter()->transactionBegin();
            parent::create($idParameter, $attributes);
            if ($this->getBeanFinder()->count() == 1) {
                $user = $this->getBeanFinder()->getBean();
                $roleFinder = new RoleBeanFinder($this->getDatabaseAdapter());
                $roleFinder->setUserRole_Code('admin');
                $role = $roleFinder->getBeanFactory()->getEmptyBean([]);
                $role->set('UserRole_Code', 'admin');
                $role->set('UserRole_Name', 'Administrator');
                $role->set('UserRole_Active', true);
                $roleList = $roleFinder->getBeanFactory()->getEmptyBeanList();
                $roleList->push($role);

                $roleProcessor = new RoleBeanProcessor($this->getDatabaseAdapter());
                $roleProcessor->setBeanList($roleList);
                $roleProcessor->save();

                if ($roleFinder->count() == 1) {
                    $role = $roleFinder->getBean();
                    $permissionFinder = new PermissionBeanFinder($this->getDatabaseAdapter());
                    $permissionBeanList = $permissionFinder->getBeanList();

                    $rolePermissionFinder = new RolePermissionBeanFinder($this->getDatabaseAdapter());
                    $rolePermissionBeanList = $rolePermissionFinder->getBeanFactory()->getEmptyBeanList();

                    foreach ($permissionBeanList as $permission) {
                        $rolePermission = $rolePermissionFinder->getBeanFactory()->getEmptyBean([]);
                        $rolePermission->set('UserRole_ID', $role->get('UserRole_ID'));
                        $rolePermission->set('UserPermission_Code', $permission->get('UserPermission_Code'));
                        $rolePermissionBeanList->push($rolePermission);
                    }

                    $rolePermissionProcessor = new RolePermissionBeanProcessor($this->getDatabaseAdapter());
                    $rolePermissionProcessor->setBeanList($rolePermissionBeanList);
                    $rolePermissionProcessor->save();

                    $userRoleFinder = new UserRoleBeanFinder($this->getDatabaseAdapter());
                    $userRole = $userRoleFinder->getBeanFactory()->getEmptyBean([]);
                    $userRoleList = $userRoleFinder->getBeanFactory()->getEmptyBeanList();
                    $userRole->set('Person_ID', $user->get('Person_ID'));
                    $userRole->set('UserRole_ID', $role->get('UserRole_ID'));
                    $userRoleList->push($userRole);

                    $userRoleProcessor = new UserRoleBeanProcessor($this->getDatabaseAdapter());
                    $userRoleProcessor->setBeanList($userRoleList);
                    $userRoleProcessor->save();
                    $this->getParsContainer()->getDatabaseAdapter()->transactionCommit();
                } else {
                    throw new CoreException('Could not create user.');
                }
            } else {
                throw new CoreException('Could not create user.');
            }
        } catch (\Throwable $exception) {
            $this->getParsContainer()->getDatabaseAdapter()->transactionRollback();
            $this->getLogger()->error($exception->getMessage(), ['exception' => $exception]);
            $this->getValidationHelper()->addError('error', $exception->getMessage());
            $this->getValidationHelper()->addError('errorDetails', $exception->getTraceAsString());
        }
    }

}
