<?php


namespace Pars\Admin\Form\Data;


use Pars\Admin\Base\BaseOverview;

class FormDataOverview extends BaseOverview
{
    protected function initName()
    {
        parent::initName();
        $this->setName($this->translate('formdata.overview'));
    }

    protected function initBase()
    {
        parent::initBase();
        $this->setShowCreate(false);
        $this->setShowEdit(false);
    }


    protected function getController(): string
    {
        return 'formdata';
    }

    protected function getDetailIdFields(): array
    {
        return ['FormData_ID'];
    }

    protected function getCreateIdFields(): array
    {
        return $this->getControllerRequest()->getId()->getAttributes();
    }
}
