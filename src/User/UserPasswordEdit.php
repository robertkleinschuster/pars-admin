<?php

namespace Pars\Admin\User;

use Pars\Admin\Base\BaseEdit;

class UserPasswordEdit extends BaseEdit
{
    protected function initialize()
    {
        $this->getForm()->addPassword('User_Password', '', $this->translate('user.password'));
        parent::initialize();
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
        return ['Person_ID'];
    }
}
