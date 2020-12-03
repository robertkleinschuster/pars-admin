<?php

namespace Pars\Admin\UserRole;


use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\SystemNavigation;
use Pars\Admin\Role\RoleController;
use Pars\Admin\Role\RoleOverview;

class UserRoleController extends RoleController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('userrole.create', 'disabled', 'userrole.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('userrole');
    }
    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('system');
        $subNavigation = new SystemNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('user');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
        $this->getView()->set('Person_ID', (int) $this->getControllerRequest()->getId()->getAttribute('Person_ID'));
    }


    protected function createOverview(): BaseOverview
    {
        $overview = new UserRoleOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $overview->setShowEdit(false);
        return $overview;
    }

    protected function createDetail(): BaseDetail
    {
        $detail = new UserRoleDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $detail;
    }


    protected function createEdit(): BaseEdit
    {
        $edit = new UserRoleEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $overview = new RoleOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $overview->setBeanList($this->getModel()->getRoleBeanList($this->getUserBean()->getPermissions(), $this->getControllerRequest()->getId()));
        $overview->setShowDeleteBulk(false);
        $overview->setShowCreate(false);
        $overview->setShowEdit(false);
        $overview->setShowDetail(false);
        $overview->setShowDelete(false);
        $edit->getForm()->push($overview);
        return $edit;
    }

    protected function createDelete(): BaseDelete
    {
        $delete = new UserRoleDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $delete;
    }
}
