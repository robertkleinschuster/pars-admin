<?php


namespace Pars\Admin\Config;


use Pars\Admin\Base\BaseEdit;
use Pars\Component\Base\Field\Headline;

class ConfigEdit extends BaseEdit
{
    protected function initialize()
    {
        $this->push(new Headline('{Config_Code}'));
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
