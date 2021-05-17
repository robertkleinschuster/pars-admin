<?php

namespace Pars\Admin\Base;

use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Navigation\Brand;
use Pars\Component\Base\Navigation\Dropdown;
use Pars\Component\Base\Navigation\Item;
use Pars\Component\Base\Navigation\Navigation;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\Parameter;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
use Pars\Pattern\Exception\AttributeNotFoundException;

/**
 * Class MainNavigation
 * @package Pars\Admin\Base
 */
class MainNavigation extends BaseNavigation
{

    public ?string $key = 'pars';

    /**
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function initialize()
    {
        if (!$this->hasBackground()) {
            $this->setBackground(Navigation::BACKGROUND_DARK);
        }
        $this->setBreakpoint(Navigation::BREAKPOINT_MEDIUM);
        $this->initContentItem();
        $this->initMediaItem();
        $this->initSystemItem();
        $this->initDropdownRight();
        $this->initBrand();
        parent::initialize();
    }

    /**
     * @return Brand
     */
    protected function initBrand(): Brand
    {
        $logo = new Icon('pars-logo');
        $logo->setWidth('100px');
        $logo->addInlineStyle('fill', '#fff');
        $logo->addInlineStyle('margin-top', '-3px');
        return $this->setBrand('', $this->getPathHelper()->setController('index')->setAction('index'))->push($logo);
    }

    /**
     * @return Item
     */
    protected function initContentItem(): Item
    {
        return $this->addItem(
            $this->translate('navigation.content'),
            $this->getPathHelper()
                ->setController('cmspage')
                ->setAction('index'),
            'content'
        )
            ->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'content'))
            ->addOption('cache');
    }

    /**
     * @return Item
     */
    protected function initMediaItem(): Item
    {
        return $this->addItem(
            $this->translate('navigation.media'),
            $this->getPathHelper()
                ->setController('filedirectory')
                ->setAction('index'),
            'media'
        )
            ->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'media'))
            ->addOption('cache');
    }


    /**
     * @return Item
     */
    protected function initSystemItem(): Item
    {
        return $this->addItem(
            $this->translate('navigation.system'),
            $this->getPathHelper()
                ->setController('user')
                ->setAction('index'),
            'system'
        )
            ->setAccept(new UserPermissionFieldAccept($this->getUserBean(), 'system'))
            ->addOption('cache');
    }

    /**
     * @return Dropdown
     */
    protected function initDropdownRight(): Dropdown
    {
        return $this->addDropdownRight($this->translate('navigation.user'), 'user')
            ->addItem(
                $this->translate('navigation.user.detail'),
                $this->getPathHelper()
                    ->setController('user')
                    ->setAction('detail')
                    ->setId((new IdParameter())
                        ->addId('Person_ID', '{Current_Person_ID}'))
            )
            ->addItem(
                $this->translate('navigation.user.locale'),
                $this->getPathHelper()
                    ->setController('user')
                    ->setAction('locale')
                    ->setId((new IdParameter())
                        ->addId('Person_ID', '{Current_Person_ID}'))
            )
            ->addItem(
                $this->translate('navigation.user.password'),
                $this->getPathHelper()
                    ->setController('user')
                    ->setAction('password')
                    ->setId((new IdParameter())
                        ->addId('Person_ID', '{Current_Person_ID}'))
            )
            ->addItem(
                $this->translate('navigation.user.clearcache'),
                $this->getPathHelper(false)
                    ->addParameter(new Parameter('clearcache', $this->key ?? 'pars'))
                    ->getPath()
            )
            ->addItem(
                $this->translate('navigation.user.logout'),
                $this->getPathHelper()
                    ->setController('auth')
                    ->setAction('logout')
            );
    }
}
