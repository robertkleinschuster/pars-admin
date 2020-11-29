<?php


namespace Pars\Admin\Config;


use Pars\Admin\Base\BaseEdit;

class ConfigEdit extends BaseEdit
{
    protected function initialize()
    {
        $this->getForm()->addText('Config_Code', '{Config_Code}', $this->translate('config.code'));
        $this->getForm()->addText('Config_Value', '{Config_Value}', $this->translate('config.value'));
        parent::initialize();
    }

    protected function getRedirectController(): string
    {
        return 'config';
    }

    protected function getRedirectIdFields(): array
    {
        return [
            'Config_Code'
        ];
    }

}
