<?php


namespace Pars\Admin\Config;


use Pars\Admin\Base\BaseDetail;

class ConfigDetail extends BaseDetail
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.config'));
        $this->setShowDelete(false);
        $this->setHeadline('{Config_Code}');
        $this->addField('Config_Value', $this->translate('config.value'));
        parent::initialize();
    }

    protected function getIndexController(): string
    {
       return 'config';
    }

    protected function getEditIdFields(): array
    {
        return [
            'Config_Code'
        ];
    }

}
