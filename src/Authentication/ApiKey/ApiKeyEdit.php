<?php


namespace Pars\Admin\Authentication\ApiKey;


use Pars\Admin\Base\BaseEdit;

class ApiKeyEdit extends BaseEdit
{
    protected function initialize()
    {
        $this->getForm()->setUseColumns(false);
        $this->getForm()->addText('ApiKey_Name', '{ApiKey_Name}', $this->translate('apikey.name'));
        $this->getForm()->addText('ApiKey_Key', '{ApiKey_Key}', $this->translate('apikey.key'));
        $this->getForm()->addUrl('ApiKey_Host', '{ApiKey_Host}', $this->translate('apikey.host'));
        $this->getForm()->addCheckbox('ApiKey_Active', '{ApiKey_Active}', $this->translate('apikey.active'));
        parent::initialize();
    }


    protected function getRedirectController(): string
    {
        return 'apikey';
    }

    protected function getRedirectIdFields(): array
    {
        return ['ApiKey_ID'];
    }

}
