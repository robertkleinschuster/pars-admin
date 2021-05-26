<?php


namespace Pars\Admin\Form\Field;


use Pars\Admin\Base\BaseDelete;

class FormFieldDelete extends BaseDelete
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
