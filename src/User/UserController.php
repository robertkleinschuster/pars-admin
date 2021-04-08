<?php

namespace Pars\Admin\User;

use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
use Pars\Pattern\Exception\AttributeNotFoundException;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\SystemNavigation;

/**
 * Class UserController
 * @package Pars\Admin\Controller
 * @method UserModel getModel()
 */
class UserController extends CrudController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('user.create', 'user.edit', 'user.delete');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('system');
        $subNavigation = new SystemNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('user');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

    /**
     * @return bool
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    public function isAuthorized(): bool
    {
        return $this->checkPermission('user') || $this->isCurrentUser();
    }

    /**
     * @return bool
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function isCurrentUser(): bool
    {
        return $this->getControllerRequest()->hasId()
            && $this->getControllerRequest()->getId()->getAttribute('Person_ID') == $this->getUserBean()->Person_ID;
    }

    public function indexAction()
    {
        $this->addFilter_Select(
            'UserState_Code',
            $this->translate('userstate.code'),
            $this->getModel()->getUserState_Options(true),
            1,
            1
        );
        $this->addFilter_Select(
            'Locale_Code',
            $this->translate('locale.code'),
            $this->getModel()->getLocale_Options(true),
            1,
            2
        );
        return parent::indexAction();
    }


    /**
     * @return BaseDetail|void
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    public function detailAction()
    {
        $detail = parent::detailAction();
        $this->getView()->set('Person_ID', (int)$this->getControllerRequest()->getId()->getAttribute('Person_ID'));
        if ($this->getUserBean()->hasPermission('userrole')) {
            $this->pushAction('userrole', 'index', $this->translate('section.role'));
        }
        return $detail;
    }

    protected function createEdit(): BaseEdit
    {
        $edit = parent::createEdit();
        $edit->setStateOptions($this->getModel()->getUserState_Options());
        $edit->setLocaleOptions($this->getModel()->getLocale_Options());
        return $edit;
    }

    public function passwordAction()
    {
        $edit = new UserPasswordEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $edit->setBean($this->getModel()->getBean());
        $this->getModel()->getBeanConverter()
            ->convert($edit->getBean(), $this->getPreviousAttributes())->fromArray($this->getPreviousAttributes());
        $edit->setToken($this->generateToken('submit_token'));
        $this->getView()->append($edit);
    }

    public function localeAction()
    {
        $edit = new UserLocaleEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->setLocaleOptions($this->getModel()->getLocale_Options());
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $edit->setBean($this->getModel()->getBean());
        $this->getModel()->getBeanConverter()
            ->convert($edit->getBean(), $this->getPreviousAttributes())->fromArray($this->getPreviousAttributes());
        $edit->setToken($this->generateToken('submit_token'));
        $this->getView()->append($edit);
    }
}
