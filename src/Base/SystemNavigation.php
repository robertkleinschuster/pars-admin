<?php

namespace Pars\Admin\Base;

class SystemNavigation extends BaseNavigation
{

    protected function initialize()
    {
        $this->addItem(
            $this->translate('navigation.system.user'),
            $this->getPathHelper()->setController('user')->setAction('index'),
            'user'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'user'));
        $this->addItem(
            $this->translate('navigation.system.role'),
            $this->getPathHelper()->setController('role')->setAction('index'),
            'role'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'role'));
        $this->addItem(
            $this->translate('navigation.system.locale'),
            $this->getPathHelper()->setController('locale')->setAction('index'),
            'locale'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'locale'));
        $this->addItem(
            $this->translate('navigation.system.update'),
            $this->getPathHelper()->setController('update')->setAction('index'),
            'update'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'update'));
        $this->setBrand(
            $this->translate('navigation.system'),
            $this->getPathHelper()->setController('user')->setAction('index')
        );
        parent::initialize();
    }
}
