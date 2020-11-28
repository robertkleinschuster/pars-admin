<?php

namespace Pars\Admin\RolePermission;

use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\SystemNavigation;


/**
 * Class RolePermissionController
 * @package Pars\Admin\RolePermission
 * @method RolePermissionModel getModel()
 */
class RolePermissionController extends CrudController
{

    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('rolepermission.create', 'rolepermission.edit', 'rolepermission.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('rolepermission');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('system');
        $subNavigation = new SystemNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('user');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
        $this->getView()->set('UserRole_ID', (int) $this->getControllerRequest()->getId()->getAttribute('UserRole_ID'));
    }

    protected function createOverview(): BaseOverview
    {
        $overview = new RolePermissionOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $overview->setShowEdit(false);
        return $overview;
    }

    protected function createDetail(): BaseDetail
    {
        $detail = new RolePermissionDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $detail;
    }


    protected function createEdit(): BaseEdit
    {
        $edit = new RolePermissionEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->setPermissionBeanList($this->getModel()->getPermissionBeanList($this->getUserBean()->getPermissions(), $this->getControllerRequest()->getId()));
        return $edit;
    }

    protected function createDelete(): BaseDelete
    {
        $delete = new RolePermissionDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $delete;
    }


}
