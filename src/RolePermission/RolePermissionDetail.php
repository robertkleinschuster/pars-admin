<?php


namespace Pars\Admin\RolePermission;


use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Span;

class RolePermissionDetail extends BaseDetail
{
    protected function initialize()
    {
        $this->addField('UserPermission_Code', $this->translate('userpermission.code'));
        $this->append((new Span(null, $this->translate('userpermission.description')))->setFormat(new RolePermissionDescriptionFieldFormat($this->getTranslator())));
        $this->addField('UserPermission_Code', $this->translate('userpermission.code'));
        $this->setShowEdit(false);
        parent::initialize();
    }


    protected function getIndexController(): string
    {
        return 'role';
    }

    protected function getEditController(): string
    {
        return 'rolepermission';
    }


    protected function getEditIdFields(): array
    {
        return [
            'UserRole_ID', 'UserPermission_Code'
        ];
    }

    protected function getIndexAction(): string
    {
        return 'detail';
    }

    protected function getIndexIdFields(): array
    {
        return [
            'UserRole_ID'
        ];
    }

}
