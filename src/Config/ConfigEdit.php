<?php

namespace Pars\Admin\Config;

use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BooleanValueFieldAccept;
use Pars\Component\Base\Field\Headline;

class ConfigEdit extends BaseEdit
{
    /**
     *
     */
    protected function initialize()
    {
        $this->push(new Headline('{Config_Code}'));
        $this->getForm()->addText('Config_Description', '{Config_Description}', $this->translate('config.description'))
            ->getInput()->setDisabled(true);
        if ($this->hasBean() && !$this->getBean()->empty('Config_Options') && count($this->getBean()->get('Config_Options'))) {
            $options = [];
            $options[] = null;
            foreach ($this->getBean()->get('Config_Options') as $option) {
                $options[$option] = $option;
            }
            $this->getForm()->addSelect('Config_Value', $options, '{Config_Value}', $this->translate('config.value'))
                ->setAccept(new BooleanValueFieldAccept('Config_Locked', true));
        } else {
            $this->getForm()->addText('Config_Value', '{Config_Value}', $this->translate('config.value'))
                ->setAccept(new BooleanValueFieldAccept('Config_Locked', true));
        }
        parent::initialize();
    }

    /**
     * @return string
     */
    protected function getRedirectController(): string
    {
        return 'config';
    }

    /**
     * @return string[]
     */
    protected function getRedirectIdFields(): array
    {
        return [
            'Config_Code', 'ConfigType_Code'
        ];
    }
}
