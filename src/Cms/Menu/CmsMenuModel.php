<?php

namespace Pars\Admin\Cms\Menu;

use Pars\Admin\Base\CrudModel;
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
class CmsMenuModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsMenuBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsMenuBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }


    public function getCmsPage_Options(): array
    {
        $options = [];
        $finder = new CmsPageBeanFinder($this->getDbAdpater());
        $finder->setLocale_Code($this->getTranslator()->getLocale());
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsPage_ID')] = $bean->getData('ArticleTranslation_Name');
        }
        return $options;
    }

    public function getCmsMenuState_Options(): array
    {
        $options = [];
        $finder = new CmsMenuStateBeanFinder($this->getDbAdpater());
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsMenuState_Code')] = $bean->getData('CmsMenuState_Code');
        }
        return $options;
    }

    public function getCmsMenuType_Options(): array
    {
        $options = [];
        $finder = new CmsMenuTypeBeanFinder($this->getDbAdpater());
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->getData('CmsMenuType_Code')] = $bean->getData('CmsMenuType_Code');
        }
        return $options;
    }
}
