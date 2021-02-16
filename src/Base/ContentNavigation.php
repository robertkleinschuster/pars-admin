<?php

namespace Pars\Admin\Base;

use Pars\Component\Base\Navigation\Brand;
use Pars\Component\Base\Navigation\Item;
use Pars\Component\Base\Navigation\Navigation;

class ContentNavigation extends BaseNavigation
{
    protected function initialize()
    {
        $this->setBackground(Navigation::BACKGROUND_LIGHT);
        $this->setBreakpoint(Navigation::BREAKPOINT_LARGE);
        $this->addOption('ajax')->addOption('history')->addOption('remote')->setData('component', 'components');
        $this->initCmsPageItem();
        $this->initCmsParagraphItem();
        $this->initCmsMenuItem();
        $this->initBrand();
        parent::initialize();
    }

    /**
     * @return Item
     */
    protected function initCmsPageItem(): Item
    {
        return $this->addItem(
            $this->translate('navigation.content.cmspage'),
            $this->getPathHelper()->setController('cmspage')->setAction('index'),
            'cmspage'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'cmspage'))->addOption('cache');
    }

    /**
     * @return Item
     */
    protected function initCmsParagraphItem(): Item
    {
        return $this->addItem(
            $this->translate('navigation.content.cmsparagraph'),
            $this->getPathHelper()->setController('cmsparagraph')->setAction('index'),
            'cmsparagraph'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'cmsparagraph'))->addOption('cache');
    }

    /**
     * @return Item
     */
    protected function initCmsMenuItem(): Item
    {
        return $this->addItem(
            $this->translate('navigation.content.cmsmenu'),
            $this->getPathHelper()->setController('cmsmenu')->setAction('index'),
            'cmsmenu'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'cmsmenu'))->addOption('cache');
    }

    /**
     * @return Brand
     */
    protected function initBrand(): Brand
    {
        return $this->setBrand(
            $this->translate('navigation.content'),
            $this->getPathHelper()->setController('cmspage')->setAction('index')
        )->addOption('cache');
    }
}
