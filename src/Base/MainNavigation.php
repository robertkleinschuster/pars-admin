<?php


namespace Pars\Admin\Base;



use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Navigation\DropdownItem;
use Pars\Component\Base\Navigation\Item;
use Pars\Component\Base\Navigation\Link;
use Pars\Helper\Parameter\IdParameter;
use Pars\Mvc\View\HtmlElement;


class MainNavigation extends BaseNavigation
{
    protected function initialize()
    {
        $this->addItem(
            $this->translate('navigation.content')
            , $this->getPathHelper()->setController('cmsmenu')->setAction('index'),
            'content')->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'content'));
        $this->addItem(
            $this->translate('navigation.media')
            , $this->getPathHelper()->setController('file')->setAction('index'),
            'media')->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'media'));
        $this->addItem(
            $this->translate('navigation.system')
            , $this->getPathHelper()->setController('user')->setAction('index'),
            'system')->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'system'));

        $this->addDropdownRight(
            $this->translate('navigation.user'),
            'user')
            ->addItem($this->translate('navigation.user.password'), $this->getPathHelper()->setController('user')->setAction('password')->setId((new IdParameter())->addId('Person_ID', '{Current_Person_ID}')))
            ->addItem($this->translate('navigation.user.clearcache'), $this->getPathHelper()->setController('index')->setAction('clearcache'))
            ->addItem($this->translate('navigation.user.logout'), $this->getPathHelper()->setController('auth')->setAction('logout'));

        $logo = new Icon('pars-logo');
        $logo->setWidth('100px');
        $logo->addInlineStyle('fill', '#fff');
        $logo->addInlineStyle('margin-top', '-7px');
        $this->setBrand('', $this->getPathHelper()->setController('index')->setAction('index'))->push($logo);
        parent::initialize();
    }
}
