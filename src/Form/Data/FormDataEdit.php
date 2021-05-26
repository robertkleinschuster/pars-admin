<?php


namespace Pars\Admin\Form\Data;


use Pars\Admin\Base\BaseEdit;

class FormDataEdit extends BaseEdit
{
    protected function getRedirectController(): string
    {
        return 'form';
    }

    protected function getRedirectIdFields(): array
    {
        return ['Form_ID',];
    }
}
