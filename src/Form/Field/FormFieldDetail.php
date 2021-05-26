<?php


namespace Pars\Admin\Form\Field;


use Pars\Admin\Base\BaseDetail;

class FormFieldDetail extends BaseDetail
{

    protected function initName()
    {
        parent::initName();
        $this->setName('{FormField_Code}');
    }

    protected function initFields()
    {
        parent::initFields();
        $this->addSpan('FormField_Code', $this->translate('formfield.code'));
        $this->addSpan('FormField_Required', $this->translate('formfield.required'));
        $this->addSpan('FormFieldType_Code', $this->translate('formfieldtype.code'));
    }

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
        return 'formfield';
    }


    protected function getEditIdFields(): array
    {
        return ['FormField_ID'];
    }

}
