<?php

namespace Pars\Admin\Authentication\ApiKey;

use Pars\Admin\Base\CrudModel;
use Pars\Model\Authentication\ApiKey\ApiKeyBeanFinder;
use Pars\Model\Authentication\ApiKey\ApiKeyBeanProcessor;

class ApiKeyModel extends CrudModel
{
    public function initialize()
    {
        parent::initialize();
        $this->setBeanFinder(new ApiKeyBeanFinder($this->getDatabaseAdapter()));
        $this->setBeanProcessor(new ApiKeyBeanProcessor($this->getDatabaseAdapter()));
    }

}
