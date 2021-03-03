<?php

namespace Pars\Admin\Role;

use Pars\Admin\Base\BaseOverview;
use Pars\Component\Base\Field\Badge;

class RoleOverview extends BaseOverview
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.role'));

        $this->addField('UserRole_Name', $this->translate('userrole.name'));
        $this->addField('UserRole_Code', $this->translate('userrole.code'));
        $active = new Badge('{UserRole_Active}');
        $active->setFormat(new RoleActiveFieldFormat($this->getTranslator()));
        $this->append($active);
        parent::initialize();
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
