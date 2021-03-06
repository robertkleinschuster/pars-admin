<?php

namespace Pars\Admin\Base;

use Pars\Component\Base\Navigation\Item;
use Pars\Component\Base\Navigation\Navigation;

class MediaNavigation extends BaseNavigation
{
    protected function initialize()
    {
        $this->setBackground(Navigation::BACKGROUND_LIGHT);
        $this->setBreakpoint(Navigation::BREAKPOINT_LARGE);

        $this->addItem(
            $this->translate('navigation.media.directory'),
            $this->getPathHelper()->setController('filedirectory')->setAction('index'),
            'filedirectory'
        )
            ->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'filedirectory'))->addOption('cache');

        $this->addItem(
            $this->translate('navigation.media.file'),
            $this->getPathHelper()->setController('file')->setAction('index'),
            'file'
        )
            ->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'file'))->addOption('cache');

        $this->setBrand(
            $this->translate('navigation.media'),
            $this->getPathHelper()->setController('file')->setAction('index')
        )->addOption('cache');
        parent::initialize();
    }

    protected function initDirectoryItem(): Item
    {
    }
}
