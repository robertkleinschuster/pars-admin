<?php

namespace Pars\Admin\Config;

use Pars\Admin\Base\CrudModel;
use Pars\Model\Config\ConfigBeanFinder;
use Pars\Model\Config\ConfigBeanProcessor;

class ConfigModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanProcessor(new ConfigBeanProcessor($this->getDbAdpater()));
        $this->setBeanFinder(new ConfigBeanFinder($this->getDbAdpater()));
    }
}
