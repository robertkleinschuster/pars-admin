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
            $this->translate('navigation.system.translation'),
            $this->getPathHelper()->setController('translation')->setAction('index'),
            'translation'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'translation'));
        $this->addItem(
            $this->translate('navigation.system.config'),
            $this->getPathHelper()->setController('config')->setAction('index'),
            'config'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'config'));
       /* $this->addItem(
            $this->translate('navigation.system.import'),
            $this->getPathHelper()->setController('import')->setAction('index'),
            'import'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'import'));*/
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
