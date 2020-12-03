<?php


namespace Pars\Admin\Config;


use Pars\Admin\Base\BaseOverview;

class ConfigOverview extends BaseOverview
{

    protected function initialize()
    {
        $this->setSection($this->translate('section.config'));
        $this->setShowDelete(false);
        $this->setShowDeleteBulk(false);
        $this->setShowCreate(false);
        $this->addField('Config_Code', $this->translate('config.code'));
        $this->addField('Config_Value', $this->translate('config.value'));
        parent::initialize();
    }

    protected function getController(): string
    {
       return 'config';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'Config_Code'
        ];
    }

}
