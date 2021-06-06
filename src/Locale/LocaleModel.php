<?php

namespace Pars\Admin\Locale;

use Pars\Bean\Processor\BeanOrderProcessor;
use Pars\Admin\Base\CrudModel;
use Pars\Model\Localization\Locale\LocaleBeanFinder;
use Pars\Model\Localization\Locale\LocaleBeanProcessor;

class LocaleModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new LocaleBeanFinder($this->getDatabaseAdapter()));
        $this->setBeanProcessor(new LocaleBeanProcessor($this->getDatabaseAdapter()));
        $this->setBeanOrderProcessor(new BeanOrderProcessor(
            new LocaleBeanProcessor($this->getDatabaseAdapter()),
            new LocaleBeanFinder($this->getDatabaseAdapter()),
            'Locale_Order'
        ));
    }
}
