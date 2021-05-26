<?php


namespace Pars\Admin\Form\Field;


use Pars\Admin\Base\BaseOverview;

class FormFieldOverview extends BaseOverview
{
    protected function initName()
    {
        parent::initName();
        $this->setName($this->translate('formfield.overview'));
    }

    protected function initBase()
    {
        parent::initBase();
        $this->setShowMove(true);
    }


    protected function initFields()
    {
        parent::initFields();
        $this->addField('FormFieldType_Code', $this->translate('formfieldtype.code'));
        $this->addField('FormField_Code', $this->translate('formfield.code'));
        $this->addField('FormField_Required', $this->translate('formfield.required'));

    }


    protected function getController(): string
    {
        return 'formfield';
    }

    protected function getDetailIdFields(): array
    {
        return ['FormField_ID'];
    }

    protected function getCreateIdFields(): array
    {
        return $this->getControllerRequest()->getId()->getAttributes();
    }

}
