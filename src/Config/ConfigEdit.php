<?php


namespace Pars\Admin\Config;


use Pars\Admin\Base\BaseEdit;
use Pars\Component\Base\Field\Headline;

class ConfigEdit extends BaseEdit
{
    protected function initialize()
    {
        $this->push(new Headline('{Config_Code}'));
        $this->getForm()->addText('Config_Description', '{Config_Description}', $this->translate('config.description'))
            ->getInput()->setDisabled(true);
        if (!$this->getBean()->empty('Config_Options') && count($this->getBean()->get('Config_Options'))) {
            $options = [];
            $options[] = null;
            foreach ($this->getBean()->get('Config_Options') as $option) {
                $options[$option] = $option;
            }
            $this->getForm()->addSelect('Config_Value', $options, '{Config_Value}', $this->translate('config.value'));
        } else {
            $this->getForm()->addText('Config_Value', '{Config_Value}', $this->translate('config.value'));
        }
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
