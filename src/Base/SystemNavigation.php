<?php

namespace Pars\Admin\Base;

use Pars\Component\Base\Navigation\Navigation;

class SystemNavigation extends BaseNavigation
{

    protected function initialize()
    {
        $this->setBackground(Navigation::BACKGROUND_LIGHT);
        $this->setBreakpoint(Navigation::BREAKPOINT_LARGE);
        $this->addOption('ajax')->addOption('history')->addOption('remote')->setData('component', 'components');

        $this->addItem(
            $this->translate('navigation.system.user'),
            $this->getPathHelper()->setController('user')->setAction('index'),
            'user'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'user'))->addOption('cache');
        $this->addItem(
            $this->translate('navigation.system.role'),
            $this->getPathHelper()->setController('role')->setAction('index'),
            'role'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'role'))->addOption('cache');
        $this->addItem(
            $this->translate('navigation.system.locale'),
            $this->getPathHelper()->setController('locale')->setAction('index'),
            'locale'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'locale'))->addOption('cache');
        $this->addItem(
            $this->translate('navigation.system.translation'),
            $this->getPathHelper()->setController('translation')->setAction('index'),
            'translation'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'translation'))->addOption('cache');
        $this->addItem(
            $this->translate('navigation.system.apikey'),
            $this->getPathHelper()->setController('apikey')->setAction('index'),
            'apikey'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'config'))->addOption('cache');
        $this->addItem(
            $this->translate('navigation.system.config'),
            $this->getPathHelper()->setController('config')->setAction('index'),
            'config'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'config'))->addOption('cache');
        $this->addItem(
            $this->translate('navigation.system.import'),
            $this->getPathHelper()->setController('import')->setAction('index'),
            'import'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'import'))->addOption('cache');
        $this->addItem(
            $this->translate('navigation.system.update'),
            $this->getPathHelper()->setController('update')->setAction('index'),
            'update'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'update'))->addOption('cache');
        $this->setBrand(
            $this->translate('navigation.system'),
            $this->getPathHelper()->setController('user')->setAction('index')
        )->addOption('cache');
        parent::initialize();
    }
}
