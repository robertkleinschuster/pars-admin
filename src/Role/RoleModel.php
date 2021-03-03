<?php

namespace Pars\Admin\Role;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\CrudModel;
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
        $this->setBeanFinder(new RoleBeanFinder($this->adapter));
        $this->setBeanProcessor(new RoleBeanProcessor($this->adapter));
    }

    public function getEmptyBean(array $data = []): BeanInterface
    {
        $bean = parent::getEmptyBean($data);
        $bean->set('UserRole_Active', true);
        return $bean;
    }
}
