<?php


namespace Pars\Admin\RolePermission;


use Pars\Admin\Base\BaseOverview;

class RolePermissionOverview extends BaseOverview
{
    protected function initialize()
    {
        $this->setShowDetail(false);
        $this->addField('UserPermission_Code', $this->translate('userpermission.code'));
        parent::initialize(); // TODO: Change the autogenerated stub
    }


    protected function getController(): string
    {
        return 'rolepermission';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'UserPermission_Code', 'UserRole_ID'
        ];
    }

    protected function getCreateIdFields(): array
    {
        return [
            'UserRole_ID'
        ];
    }

}
