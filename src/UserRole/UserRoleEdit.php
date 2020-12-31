<?php


namespace Pars\Admin\UserRole;


use Pars\Admin\Role\RoleEdit;
use Pars\Model\Authorization\Role\RoleBeanList;

class UserRoleEdit extends RoleEdit
{
    protected ?RoleBeanList $roleBeanList = null;

    protected function initFields()
    {
        $this->setCreateBulk(true);
    }


    /**
     * @return RoleBeanList
     */
    public function getRoleBeanList(): RoleBeanList
    {
        return $this->roleBeanList;
    }

    /**
     * @param RoleBeanList $roleBeanList
     *
     * @return $this
     */
    public function setRoleBeanList(RoleBeanList $roleBeanList): self
    {
        $this->roleBeanList = $roleBeanList;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasRoleBeanList(): bool
    {
        return isset($this->roleBeanList);
    }


    protected function getRedirectController(): string
    {
        return 'user';
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

    protected function getRedirectIdFields(): array
    {
        return [
            'Person_ID'
        ];
    }

    protected function getCreateRedirectAction(): string
    {
        return 'detail';
    }

    protected function getCreateRedirectIdFields(): array
    {
        return ['Person_ID'];
    }


}
