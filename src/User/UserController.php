<?php

namespace Pars\Admin\User;

use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
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
     */
    public function isAuthorized(): bool
    {
        return $this->checkPermission('user')
            || $this->getControllerRequest()->hasId() && $this->getControllerRequest()->getId()->getAttribute('Person_ID') == $this->getUserBean()->Person_ID;
    }

    protected function createOverview(): BaseOverview
    {
        $overview = new UserOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $overview;
    }

    public function detailAction()
    {
        parent::detailAction();
        $this->getView()->set('Person_ID', (int)$this->getControllerRequest()->getId()->getAttribute('Person_ID'));
        if ($this->getUserBean()->hasPermission('userrole')) {
            $this->pushAction('userrole', 'index', $this->translate('section.role'));
        }
    }


    protected function createDetail(): BaseDetail
    {
        return new UserDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }


    protected function createEdit(): BaseEdit
    {
        $edit = new UserEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
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


    protected function createDelete(): BaseDelete
    {
        $delete = new UserDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $delete;
    }
}
