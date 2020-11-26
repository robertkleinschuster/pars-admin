<?php


namespace Pars\Admin\Base;


class MediaNavigation extends BaseNavigation
{
    protected function initialize()
    {
        /**
         *  'navigation.media.file' => 'Dateien',
        'navigation.media.directory' => 'Order',
         */
        $this->addItem(
            $this->translate('navigation.media.file')
            , $this->getPathHelper()->setController('file')->setAction('index'),
            'cmsmenu')
            ->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'cmsmenu'));
        $this->addItem(
            $this->translate('navigation.media.directory')
            , $this->getPathHelper()->setController('directory')->setAction('index'),
            'cmspage')
            ->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'cmspage'));
        $this->setBrand(
            $this->translate('navigation.media'),
            $this->getPathHelper()->setController('file')->setAction('index')
        );
        parent::initialize();
    }

}
