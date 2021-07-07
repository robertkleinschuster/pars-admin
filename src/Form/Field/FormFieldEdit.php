<?php


namespace Pars\Admin\Form\Field;


use Pars\Admin\Base\BaseEdit;

class FormFieldEdit extends BaseEdit
{

    protected ?array $typeOptions = null;

    protected function initFields()
    {
        parent::initFields();
        $this->getForm()->setUseColumns(false);
        $this->getForm()->addText('FormField_Code', '{FormField_Code}', $this->translate('formfield.code'));
        $this->getForm()->addCheckbox('FormField_Required', '{FormField_Required}', $this->translate('formfield.required'));
        if ($this->hasTypeOptions()) {
            $this->getForm()->addSelect('FormFieldType_Code', $this->getTypeOptions(), '{FormFieldType_Code}', $this->translate('formfieldtype.code'));
        }
    }


    protected function getRedirectController(): string
    {
        return 'form';
    }

    protected function getRedirectIdFields(): array
    {
        return ['Form_ID',];
    }

    /**
    * @return array
    */
    public function getTypeOptions(): array
    {
        return $this->typeOptions;
    }

    /**
    * @param array $typeOptions
    *
    * @return $this
    */
    public function setTypeOptions(array $typeOptions): self
    {
        $this->typeOptions = $typeOptions;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasTypeOptions(): bool
    {
        return isset($this->typeOptions);
    }


}
