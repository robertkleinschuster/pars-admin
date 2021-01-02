<?php

namespace Pars\Admin\Cms\Menu;

use Niceshops\Bean\Processor\BeanOrderProcessor;
use Pars\Admin\Base\CrudModel;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Cms\Menu\CmsMenuBeanFinder;
use Pars\Model\Cms\Menu\CmsMenuBeanProcessor;
use Pars\Model\Cms\Menu\State\CmsMenuStateBeanFinder;
use Pars\Model\Cms\Menu\Type\CmsMenuTypeBeanFinder;
use Pars\Model\Cms\Page\CmsPageBeanFinder;

/**
 * Class CmsMenuModel
 * @package Pars\Admin\Cms\Menu
 * @method CmsMenuBeanFinder getBeanFinder() : BeanFinderInterface
 */
class CmsSubMenuModel extends CmsMenuModel
{
    protected function initFinder() {

    }
}
