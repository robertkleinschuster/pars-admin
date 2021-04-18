<?php

namespace Pars\Admin\UserRole;

use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\SystemNavigation;
use Pars\Admin\Role\RoleController;
use Pars\Admin\Role\RoleOverview;

/**
 * Class UserRoleController
 * @package Pars\Admin\UserRole
 * @method UserRoleModel getModel()
 */
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
        $subNavigation = new SystemNavigation($this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('user');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
        $this->getView()->set('Person_ID', (int)$this->getControllerRequest()->getId()->getAttribute('Person_ID'));
    }


    protected function createOverview(): BaseOverview
    {
        $overview = new UserRoleOverview($this->getTranslator(), $this->getUserBean());
        $overview->setShowEdit(false);
        return $overview;
    }


    protected function createEdit(): BaseEdit
    {
        $edit = new UserRoleEdit($this->getTranslator(), $this->getUserBean());
        $overview = new RoleOverview( $this->getTranslator(), $this->getUserBean());
        $overview->setBeanList($this->getModel()->getRoleBeanList($this->getUserBean()->getPermissions(), $this->getControllerRequest()->getId()));
        $overview->setShowDeleteBulk(false);
        $overview->setShowCreate(false);
        $overview->setShowEdit(false);
        $overview->setShowDetail(false);
        $overview->setShowDelete(false);
        $edit->getForm()->push($overview);
        return $edit;
    }
}
