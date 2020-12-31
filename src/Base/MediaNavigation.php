<?php


namespace Pars\Admin\Base;


class MediaNavigation extends BaseNavigation
{
    protected function initialize()
    {
        /**
         *  'navigation.media.file' => 'Dateien',
         * 'navigation.media.directory' => 'Order',
         */
        $this->addItem(
            $this->translate('navigation.media.file')
            , $this->getPathHelper()->setController('file')->setAction('index'),
            'file')
            ->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'file'));
        $this->addItem(
            $this->translate('navigation.media.directory')
            , $this->getPathHelper()->setController('filedirectory')->setAction('index'),
            'filedirectory')
            ->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'filedirectory'));
        $this->setBrand(
            $this->translate('navigation.media'),
            $this->getPathHelper()->setController('file')->setAction('index')
        );
        parent::initialize();
    }

}
