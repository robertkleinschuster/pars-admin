<?php


namespace Pars\Admin\RolePermission;


use Pars\Admin\Base\BaseEdit;
use Pars\Model\Authorization\Permission\PermissionBeanList;

class RolePermissionEdit extends BaseEdit
{

    private ?PermissionBeanList $permissionBeanList = null;

    protected function initialize()
    {
        $options = [];
        if ($this->hasPermissionBeanList()) {
            foreach ($this->getPermissionBeanList() as $permission) {
                $options[$permission->get('UserPermission_Code')] = $permission->get('UserPermission_Code');
            }
        }
        $this->getForm()->addSelect('UserPermission_Code', $options, '{UserPermission_Code}', $this->translate('userpermission.code'));
        parent::initialize();
    }

    /**
    * @return PermissionBeanList
    */
    public function getPermissionBeanList(): PermissionBeanList
    {
        return $this->permissionBeanList;
    }

    /**
    * @param PermissionBeanList $permissionBeanList
    *
    * @return $this
    */
    public function setPermissionBeanList(PermissionBeanList $permissionBeanList): self
    {
        $this->permissionBeanList = $permissionBeanList;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasPermissionBeanList(): bool
    {
        return isset($this->permissionBeanList);
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
        return [
            'UserRole_ID'
        ];
    }

    protected function getCreateRedirectAction(): string
    {
        return 'detail';
    }

    protected function getCreateRedirectIdFields(): array
    {
        return [
            'UserRole_ID'
        ];
    }


}
