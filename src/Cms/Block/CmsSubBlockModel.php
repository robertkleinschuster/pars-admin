<?php

namespace Pars\Admin\Cms\Block;

use Pars\Bean\Processor\BeanOrderProcessor;
use Pars\Model\Cms\Block\CmsBlockBeanFinder;
use Pars\Model\Cms\Block\CmsBlockBeanProcessor;

class CmsSubBlockModel extends CmsBlockModel
{
    /**
     *
     */
    protected function initFinder()
    {
        $this->setBeanOrderProcessor(new BeanOrderProcessor(
            new CmsBlockBeanProcessor($this->getDatabaseAdapter()),
            (new CmsBlockBeanFinder($this->getDatabaseAdapter()))->filterLocale_Code($this->getTranslator()->getLocale()),
            'CmsBlock_Order',
            'CmsBlock_ID_Parent'
        ));
        $this->getBeanFinder()->order(['CmsBlock_Order']);
    }

}
