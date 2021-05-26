<?php


namespace Pars\Admin\Form;


use Pars\Admin\Base\BaseOverview;

class FormOverview extends BaseOverview
{

    protected function initName()
    {
        parent::initName();
        $this->setName($this->translate('form.overview'));
    }



    protected function initFields()
    {
        parent::initFields();
        $this->addField('Form_Code', $this->translate('form.code'));
        $this->addField('FormType_Code', $this->translate('formtype.code'));
    }


    protected function getController(): string
    {
        return 'form';
    }

    protected function getDetailIdFields(): array
    {
        return ['Form_ID'];
    }

}
