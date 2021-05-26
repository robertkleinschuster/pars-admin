<?php


namespace Pars\Admin\Form\Data;


use Pars\Admin\Base\BaseDetail;

class FormDataDetail extends BaseDetail
{


    protected function getIndexController(): string
    {
        return 'form';
    }

    protected function getIndexIdFields(): array
    {
        return ['Form_ID'];
    }

    protected function getEditController(): string
    {
        return 'formdata';
    }


    protected function getEditIdFields(): array
    {
        return ['FormData_ID'];
    }
}
