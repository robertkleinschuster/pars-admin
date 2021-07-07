<?php

namespace Pars\Admin\Config;

use Pars\Bean\Type\Base\BeanException;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BooleanValueFieldAccept;

/**
 * Class ConfigDetail
 * @package Pars\Admin\Config
 */
class ConfigDetail extends BaseDetail
{
    protected function initName()
    {
        $this->setName('{Config_Code}');
    }


    /**
     * @throws BeanException
     */
    protected function initialize()
    {
        $this->setShowDelete(false);
        $this->setHeading('{Config_Code}');
        $this->addSpan('Config_Value', $this->translate('config.value'));
        $this->addSpan('ConfigType_Code', $this->translate('configtype.code'));
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
            'Config_Code', 'ConfigType_Code'
        ];
    }
}
