<?php


namespace Pars\Admin\Authentication\ApiKey;


use Pars\Admin\Base\BaseOverview;

class ApiKeyOverview extends BaseOverview
{
    protected function initName()
    {
        $this->setName($this->translate('section.apikey'));
    }

    protected function initialize()
    {
        $this->addField('ApiKey_Name', $this->translate('apikey.name'));
        $this->addField('ApiKey_Key', $this->translate('apikey.key'));
        $this->addField('ApiKey_Host', $this->translate('apikey.host'));
        $this->addField('ApiKey_Active', $this->translate('apikey.active'));
        parent::initialize();
    }


    protected function getController(): string
    {
        return 'apikey';
    }

    protected function getDetailIdFields(): array
    {
        return ['ApiKey_ID'];
    }

}
