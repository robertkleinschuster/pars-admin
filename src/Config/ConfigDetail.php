<?php

namespace Pars\Admin\Config;

use Niceshops\Bean\Type\Base\BeanException;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BooleanValueFieldAccept;

/**
 * Class ConfigDetail
 * @package Pars\Admin\Config
 */
class ConfigDetail extends BaseDetail
{
    /**
     * @throws BeanException
     */
    protected function initialize()
    {
        $this->setSection($this->translate('section.config'));
        $this->setShowDelete(false);
        $this->setHeading('{Config_Code}');
        $this->addField('Config_Value', $this->translate('config.value'));
        $this->addField('ConfigType_Code', $this->translate('configtype.code'));
        $this->setShowEditFieldAccept(new BooleanValueFieldAccept('Config_Locked', true));
        parent::initialize();
    }

    /**
     * @return string
     */
    protected function getIndexController(): string
    {
        return 'config';
    }

    /**
     * @return string[]
     */
    protected function getEditIdFields(): array
    {
        return [
            'Config_Code'
        ];
    }
}
