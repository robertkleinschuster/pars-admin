<?php

namespace Pars\Admin\Role;

use Pars\Admin\Base\BaseEdit;

class RoleEdit extends BaseEdit
{
    protected function initFields()
    {
        parent::initFields();
        $this->getForm()->addText('UserRole_Code', '{UserRole_Code}', $this->translate('userrole.code'));
        $this->getForm()->addText('UserRole_Name', '{UserRole_Name}', $this->translate('userrole.name'));
        $this->getForm()->addCheckbox('UserRole_Active', '{UserRole_Active}', $this->translate('userrole.active'));
    }

    protected function getRedirectController(): string
    {
        return 'role';
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

    protected function getRedirectIdFields(): array
    {
        return ['UserRole_ID'];
    }
}
