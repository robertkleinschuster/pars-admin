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
        $this->initCmsBlockItem();
        $this->initFormItem();
        $this->initPictureItem();
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
    protected function initCmsBlockItem(): Item
    {
        return $this->addItem(
            $this->translate('navigation.content.cmsblock'),
            $this->getPathHelper()->setController('cmsblock')->setAction('index'),
            'cmsblock'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'cmsblock'))->addOption('cache');
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
     * @return Item
     */
    protected function initPictureItem(): Item
    {
        return $this->addItem(
            $this->translate('navigation.content.picture'),
            $this->getPathHelper()->setController('picture')->setAction('index'),
            'picture'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'file'))->addOption('cache');
    }

    protected function initFormItem(): Item
    {
        return $this->addItem(
            $this->translate('navigation.content.form'),
            $this->getPathHelper()->setController('form')->setAction('index'),
            'form'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'form'));

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
