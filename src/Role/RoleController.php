<?php

namespace Pars\Admin\Role;


use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\CrudController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Helper\Parameter\IdParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Overview\Fields\Badge;
use Pars\Mvc\View\Components\Overview\Overview;

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

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setId((new IdParameter())->addId('UserRole_ID'));
    }

    protected function addOverviewFields(Overview $overview): void
    {
        $overview->addBadge('UserRole_Active', $this->translate('userrole.active'))->setWidth(50)
        ->setFormat(function (BeanInterface $bean, Badge $badge) {
            if ($bean->getData('UserRole_Active')) {
                $badge->setStyle(Badge::STYLE_SUCCESS);
                return $this->translate('userrole.active.true');
            } else {
                $badge->setStyle(Badge::STYLE_DANGER);
                return $this->translate('userrole.active.false');
            }
        });
        $overview->addText('UserRole_Code', $this->translate('userrole.code'));
    }


    protected function addEditFields(Edit $edit): void
    {
        $edit->addText('UserRole_Code', $this->translate('userrole.code'));
        $edit->addCheckbox('UserRole_Active', $this->translate('userrole.active'));
    }


    protected function addDetailFields(Detail $detail): void
    {
        $detail->addText('UserRole_Code', $this->translate('userrole.code'));
        $this->addSubController('rolepermission', 'index');
    }
}
