<?php


namespace Pars\Admin\Form\Field;


use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\BooleanValueFieldFormat;
use Pars\Mvc\View\Event\ViewEvent;

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
        $this->addFieldSpan('FormField_Required', '')
         ->setFormat(new BooleanValueFieldFormat(
             'FormField_Required',
             $this->translate('formfield.required.true'),
             $this->translate('formfield.required.false')
         ));

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
