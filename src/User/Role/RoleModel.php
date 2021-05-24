<?php

namespace Pars\Admin\User\Role;

use Pars\Admin\Base\CrudModel;
use Pars\Bean\Type\Base\BeanInterface;
use Pars\Model\Authorization\Role\RoleBeanFinder;
use Pars\Model\Authorization\Role\RoleBeanProcessor;

/**
 * Class UserRoleModel
 * @package Pars\Admin\Model
 * @method RoleBeanFinder getBeanFinder() : BeanFinderInterface
 * @method RoleBeanProcessor getProcessor() : BeanProcessorInterface
 */
class RoleModel extends CrudModel
{

    public function initialize()
    {
        $this->setBeanFinder(new RoleBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new RoleBeanProcessor($this->getDbAdpater()));
    }

    public function getEmptyBean(array $data = []): BeanInterface
    {
        $bean = parent::getEmptyBean($data);
        $bean->set('UserRole_Active', true);
        return $bean;
    }
}
