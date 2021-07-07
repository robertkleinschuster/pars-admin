<?php


namespace Pars\Admin\Form;


use Pars\Admin\Base\BaseDetail;

class FormDetail extends BaseDetail
{

    protected function initName()
    {
        parent::initName();
        $this->setName('{Form_Code}');
    }


    protected function initFields()
    {
        parent::initFields();
        $this->addSpan('Form_Code', $this->translate('form.code'));
        $this->addSpan('FormType_Code', $this->translate('formtype.code'));
    }


    protected function getIndexController(): string
    {
        return 'form';
    }

    protected function getEditIdFields(): array
    {
        return ['Form_ID'];
    }

}
