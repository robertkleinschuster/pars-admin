<?php


namespace Pars\Admin\Base;


use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Navigation\Navigation;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\Parameter;


class MainNavigation extends BaseNavigation
{
    protected function initialize()
    {
        $this->setBackground(Navigation::BACKGROUND_DARK);
        $this->setBreakpoint(Navigation::BREAKPOINT_MEDIUM);

        $this->addItem(
            $this->translate('navigation.content')
            , $this->getPathHelper()->setController('cmspage')->setAction('index'),
            'content')->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'content'))->addOption('cache');
        $this->addItem(
            $this->translate('navigation.media')
            , $this->getPathHelper()->setController('file')->setAction('index'),
            'media')->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'media'))->addOption('cache');
        $this->addItem(
            $this->translate('navigation.system')
            , $this->getPathHelper()->setController('user')->setAction('index'),
            'system')->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'system'))->addOption('cache');


        $this->addDropdownRight(
            $this->translate('navigation.user'),
            'user')
            ->addItem($this->translate('navigation.user.detail'), $this->getPathHelper()->setController('user')->setAction('detail')->setId((new IdParameter())->addId('Person_ID', '{Current_Person_ID}')))
            ->addItem($this->translate('navigation.user.locale'), $this->getPathHelper()->setController('user')->setAction('locale')->setId((new IdParameter())->addId('Person_ID', '{Current_Person_ID}')))
            ->addItem($this->translate('navigation.user.password'), $this->getPathHelper()->setController('user')->setAction('password')->setId((new IdParameter())->addId('Person_ID', '{Current_Person_ID}')))
            ->addItem($this->translate('navigation.user.clearcache'), $this->getPathHelper()->setController('index')->setAction('index')->addParameter(new Parameter('clearcache', 'pars'))->getPath())
            ->addItem($this->translate('navigation.user.logout'), $this->getPathHelper()->setController('auth')->setAction('logout'));

        $logo = new Icon('pars-logo');
        $logo->setWidth('100px');
        $logo->addInlineStyle('fill', '#fff');
        $logo->addInlineStyle('margin-top', '-7px');
        $this->setBrand('', $this->getPathHelper()->setController('index')->setAction('index'))->push($logo);
        parent::initialize();
    }
}
