<?php

namespace Pars\Admin\Locale;

use Niceshops\Bean\Processor\BeanOrderProcessor;
use Pars\Admin\Base\CrudModel;
use Pars\Model\Localization\Locale\LocaleBeanFinder;
use Pars\Model\Localization\Locale\LocaleBeanProcessor;

class LocaleModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new LocaleBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new LocaleBeanProcessor($this->getDbAdpater()));
        $this->setBeanOrderProcessor(new BeanOrderProcessor(
            new LocaleBeanProcessor($this->getDbAdpater()),
            new LocaleBeanFinder($this->getDbAdpater()),
            'Locale_Order'
        ));
    }

}
