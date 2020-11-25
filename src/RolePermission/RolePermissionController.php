<?php

namespace Pars\Admin\RolePermission;

use Pars\Admin\Base\CrudController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Helper\Parameter\IdParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Overview\Overview;

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

    protected function addEditFields(Edit $edit): void
    {
        $edit->addSelect('UserPermission_Code', $this->translate('userpermission.code'))
            ->setSelectOptions(
                $this->getModel()->getPermissionList(
                    $this->getUser()->getPermission_List(),
                    $this->getControllerRequest()->getId()
                )
            );
    }

    protected function addOverviewFields(Overview $overview): void
    {
        $overview->addText('UserPermission_Code', $this->translate('userpermission.code'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        $detail->addText('UserPermission_Code', $this->translate('userpermission.code'));
    }

    protected function getDetailPath(): PathHelper
    {
        return parent::getDetailPath()->setController('rolepermission')->setId($this->getControllerRequest()->getId()->addId('UserPermission_Code')->addId('UserRole_ID'));
    }

    protected function getCreatePath(): PathHelper
    {
        return parent::getCreatePath()->setController('rolepermission')->setId($this->getControllerRequest()->getId());
    }

    protected function getIndexPath(): PathHelper
    {
        if ($this->getControllerRequest()->getId()->hasAttribute('Person_ID')) {
            return parent::getIndexPath()->setController('userrole')->setAction('detail')->setId($this->getControllerRequest()->getId()->unsetAttribute('UserPermission_Code'));
        } else {
            return parent::getIndexPath()->setController('role')->setAction('detail')->setId($this->getControllerRequest()->getId()->unsetAttribute('UserPermission_Code'));
        }
    }


}
