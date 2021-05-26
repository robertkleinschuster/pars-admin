<?php


namespace Pars\Admin\Form\Data;


use Pars\Admin\Base\BaseDelete;

class FormDataDelete extends BaseDelete
{
    protected function getRedirectController(): string
    {
        return 'form';
    }

    protected function getRedirectIdFields(): array
    {
        return ['Form_ID'];
    }


}
