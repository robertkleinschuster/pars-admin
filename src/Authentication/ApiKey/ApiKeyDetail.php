<?php


namespace Pars\Admin\Authentication\ApiKey;


use Pars\Admin\Base\BaseDetail;

class ApiKeyDetail extends BaseDetail
{
    protected function initialize()
    {
        $this->setName('{ApiKey_Name}');
        $this->addField('ApiKey_Name', $this->translate('apikey.name'));
        $this->addField('ApiKey_Key', $this->translate('apikey.key'));
        $this->addField('ApiKey_Host', $this->translate('apikey.host'));
        $this->addField('ApiKey_Active', $this->translate('apikey.active'));
        parent::initialize();
    }


    protected function getIndexController(): string
    {
        return 'apikey';
    }

    protected function getEditIdFields(): array
    {
        return ['ApiKey_ID'];
    }
}
