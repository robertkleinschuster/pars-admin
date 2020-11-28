<?php

namespace Pars\Admin\Base;

use Pars\Model\Authentication\User\UserBean;
use Pars\Model\Authentication\User\UserBeanFinder;

abstract class CrudModel extends BaseModel
{
    public function getUserById(int $personID): UserBean
    {
        $userFinder = new UserBeanFinder($this->getDbAdpater());
        $userFinder->setPerson_ID($personID);
        return $userFinder->getBean();
    }
}
