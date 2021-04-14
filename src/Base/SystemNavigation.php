<?php

namespace Pars\Admin\Base;

use Pars\Component\Base\Navigation\Navigation;
use Pars\Helper\Parameter\FilterParameter;
use Pars\Helper\Parameter\IdParameter;

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
            $this->translate('navigation.system.style'),
            $this->getPathHelper()->setController('config')->setAction('index')->addParameter((new FilterParameter())->addId('ConfigType_Code', 'style')),
            'style'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'config'))->addOption('cache');
        $this->addItem(
            $this->translate('navigation.system.frontend'),
            $this->getPathHelper()->setController('config')->setAction('index')->addParameter((new FilterParameter())->addId('ConfigType_Code', 'frontend')),
            'frontend'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'config'))->addOption('cache');

        $this->addItem(
            $this->translate('navigation.system.import'),
            $this->getPathHelper()->setController('import')->setAction('index'),
            'import'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'import'))->addOption('cache');

        $this->addItem(
            $this->translate('navigation.system.tasklog'),
            $this->getPathHelper()->setController('tasklog')->setAction('index'),
            'tasklog'
        )->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'tasklog'));

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
