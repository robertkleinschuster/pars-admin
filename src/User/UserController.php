<?php

namespace Pars\Admin\User;

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
        return $this->checkPermission('user');
    }

    public function indexAction()
    {
        $overview = new UserOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());

        $overview->setBeanList($this->getModel()->getBeanList());
        $this->getView()->append($overview);
    }

    public function detailAction()
    {
      $detail = new UserDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
      $detail->setBean($this->getModel()->getBean());
      $this->getView()->append($detail);
    }

    public function createAction()
    {
        $edit = new UserEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->setBean($this->getModel()->getEmptyBean($this->getPreviousAttributes()));
        $edit->getBean()->fromArray($this->getPreviousAttributes());
        $this->getView()->append($edit);
    }

    public function editAction()
    {
        $edit = new UserEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $edit->setStateOptions($this->getModel()->getUserState_Options());
        $edit->setLocaleOptions($this->getModel()->getLocale_Options());
        $edit->setBean($this->getModel()->getBean());
        $edit->getBean()->fromArray($this->getPreviousAttributes());
        $edit->setToken($this->generateToken('submit_token'));
        $edit->setIndexPath($this->getPathHelper()->setController('user')->setAction('index'));
        $this->getView()->append($edit);
    }

    public function deleteAction()
    {
        $delete = new UserDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $this->getView()->append($delete);
    }


}
