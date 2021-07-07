<?php

namespace Pars\Admin\User\Role;

use Pars\Admin\Base\BaseOverview;
use Pars\Component\Base\Field\Badge;

class RoleOverview extends BaseOverview
{
    protected function initName()
    {
        $this->setName($this->translate('section.role'));
    }

    protected function initBase()
    {
        parent::initBase();
        $this->setShowOrder(true);
    }

    protected function initFields()
    {
        parent::initFields();
        $this->addFieldState('UserRole_Active');
        $this->addFieldOrderable('UserRole_Name', $this->translate('userrole.name'));
        $this->addFieldOrderable('UserRole_Code', $this->translate('userrole.code'));
    }

    protected function getController(): string
    {
        return 'role';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'UserRole_ID'
        ];
    }

    protected function getCreateIdFields(): array
    {
        return [];
    }
}
