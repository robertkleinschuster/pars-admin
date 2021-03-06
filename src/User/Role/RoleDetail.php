<?php

namespace Pars\Admin\User\Role;

use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Badge;

class RoleDetail extends BaseDetail
{
    protected function initName()
    {
        $this->setName('{UserRole_Name}');
    }


    protected function initialize()
    {

        $this->setHeading('{UserRole_Name}');
        $this->addSpan('UserRole_Code', $this->translate('userrole.code'));
        $active = new Badge('{UserRole_Active}');
        $active->setLabel($this->translate('userrole.active'));
        $active->setFormat(new RoleActiveFieldFormat($this->getTranslator()));
        $this->pushField($active);
        parent::initialize();
    }

    protected function getIndexController(): string
    {
        return 'role';
    }

    protected function getEditController(): string
    {
        return 'role';
    }


    protected function getIndexAction(): string
    {
        return 'index';
    }

    protected function getIndexIdFields(): array
    {
        return [];
    }

    protected function getEditAction(): string
    {
        return 'edit';
    }

    protected function getEditIdFields(): array
    {
        return ['UserRole_ID'];
    }
}
