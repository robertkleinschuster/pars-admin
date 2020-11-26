<?php


namespace Pars\Admin\Base;



use Pars\Component\Base\Field\Icon;


class MainNavigation extends BaseNavigation
{
    protected function initialize()
    {
        $this->addItem(
            $this->translate('navigation.content')
            , $this->getPathHelper()->setController('cmsmenu')->setAction('index'),
            'content');//->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'content'));
        $this->addItem(
            $this->translate('navigation.media')
            , $this->getPathHelper()->setController('file')->setAction('index'),
            'media');//->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'media'));
        $this->addItem(
            $this->translate('navigation.system')
            , $this->getPathHelper()->setController('user')->setAction('index'),
            'system');//->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'system'));
        $logo = new Icon('pars-logo');
        $logo->setWidth('100px');
        $logo->addInlineStyle('fill', '#fff');
        $logo->addInlineStyle('margin-top', '-7px');
        $this->setBrand('', $this->getPathHelper()->setController('index')->setAction('index'))->push($logo);
        parent::initialize();
    }
}
