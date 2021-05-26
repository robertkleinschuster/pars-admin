<?php


namespace Pars\Admin\Form;


use Pars\Admin\Base\BaseDelete;

class FormDelete extends BaseDelete
{
    protected function getRedirectController(): string
    {
        return 'form';
    }

}
