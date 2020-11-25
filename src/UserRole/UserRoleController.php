<?php

namespace Pars\Admin\UserRole;


use Pars\Admin\Role\RoleController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Mvc\View\Components\Edit\Edit;

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


    protected function addEditFields(Edit $edit): void
    {
        $edit->addSelect('UserRole_ID', 'Rolle')
            ->setSelectOptions($this->getModel()->getRoleList($this->getUser()->getPermission_List(), $this->getControllerRequest()->getId()));
    }

    protected function getCreatePath(): PathHelper
    {
        return parent::getCreatePath()->setId($this->getControllerRequest()->getId());
    }


    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setController('userrole')->setId($this->getControllerRequest()->getId()->addId('UserRole_ID'));
    }

    protected function getIndexPath(): PathHelper
    {
        return parent::getIndexPath()->setController('user')->setAction('detail')->setId($this->getControllerRequest()->getId()->unsetAttribute('UserRole_ID'));
    }

    protected function getRoleDetailRedirectPath(): PathHelper
    {
        return $this->getPathHelper()->setController('user')->setAction('detail');
    }
}
