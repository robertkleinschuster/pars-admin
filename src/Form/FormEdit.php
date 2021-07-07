<?php


namespace Pars\Admin\Form;


use Pars\Admin\Base\BaseEdit;

class FormEdit extends BaseEdit
{
    protected ?array $typeOptions = null;


    protected function initFields()
    {
        parent::initFields();
        $this->getForm()->setUseColumns(false);
        $this->getForm()->addText('Form_Code', '{Form_Code}', $this->translate('form.code'));
        $this->getForm()->addCheckbox('Form_IndexInfo', '{Form_IndexInfo}', $this->translate('form.indexinfo'));
        if ($this->hasTypeOptions()) {
            $this->getForm()->addSelect('FormType_Code', $this->getTypeOptions(), '{FormType_Code}', $this->translate('formtype.code'));
        }
    }


    protected function getRedirectController(): string
    {
        return 'form';
    }

    protected function getRedirectIdFields(): array
    {
        return ['Form_ID'];
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
