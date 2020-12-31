<?php

namespace Pars\Admin\Role;


use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\SystemNavigation;


/**
 * Class UserRoleController
 * @package Pars\Admin\Controller
 */
class RoleController extends CrudController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('role.create', 'role.edit', 'role.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('role');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('system');
        $subNavigation = new SystemNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('role');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

    protected function createOverview(): BaseOverview
    {
        $overview = new RoleOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $overview;
    }

    protected function createDetail(): BaseDetail
    {
        $detail = new RoleDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $detail;
    }

    public function detailAction()
    {
        parent::detailAction();
        $this->getView()->set('UserRole_ID', (int)$this->getControllerRequest()->getId()->getAttribute('UserRole_ID'));
        $this->addSubController('rolepermission', 'index');
    }

    protected function createEdit(): BaseEdit
    {
        $edit = new RoleEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $edit;
    }

    protected function createDelete(): BaseDelete
    {
        $delete = new RoleDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $delete;
    }

}
