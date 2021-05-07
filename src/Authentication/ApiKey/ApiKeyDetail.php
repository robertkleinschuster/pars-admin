<?php


namespace Pars\Admin\Authentication\ApiKey;


use Pars\Admin\Base\BaseDetail;

class ApiKeyDetail extends BaseDetail
{
    protected function initialize()
    {
        $this->setName('{ApiKey_Name}');
        $this->addSpan('ApiKey_Name', $this->translate('apikey.name'));
        $this->addSpan('ApiKey_Key', $this->translate('apikey.key'));
        $this->addSpan('ApiKey_Host', $this->translate('apikey.host'));
        $this->addSpan('ApiKey_Active', $this->translate('apikey.active'));
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
