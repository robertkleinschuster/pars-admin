<?php

namespace Pars\Admin\Article\Data;

use Pars\Admin\Base\CrudModel;
use Pars\Model\Article\Data\ArticleDataBeanFinder;
use Pars\Model\Article\Data\ArticleDataBeanProcessor;

class ArticleDataModel extends CrudModel
{
    public function initialize()
    {
        parent::initialize();
        $this->setBeanFinder(new ArticleDataBeanFinder($this->getDatabaseAdapter()));
        $this->setBeanProcessor(new ArticleDataBeanProcessor($this->getDatabaseAdapter()));
    }
}
