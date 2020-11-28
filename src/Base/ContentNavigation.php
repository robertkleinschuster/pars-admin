<?php


namespace Pars\Admin\Base;


class ContentNavigation extends BaseNavigation
{
    protected function initialize()
    {
        $this->addItem(
            $this->translate('navigation.content.cmsmenu')
            , $this->getPathHelper()->setController('cmsmenu')->setAction('index'),
            'cmsmenu')
            ->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'cmsmenu'));
        $this->addItem(
            $this->translate('navigation.content.cmspage')
            , $this->getPathHelper()->setController('cmspage')->setAction('index'),
            'cmspage')
            ->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'cmspage'));
        $this->addItem(
            $this->translate('navigation.content.cmsparagraph')
            , $this->getPathHelper()->setController('cmsparagraph')->setAction('index'),
            'cmsparagraph')
            ->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'cmsparagraph'));
        $this->setBrand(
            $this->translate('navigation.content'),
            $this->getPathHelper()->setController('cmsmenu')->setAction('index')
        );
        parent::initialize();
    }

}
