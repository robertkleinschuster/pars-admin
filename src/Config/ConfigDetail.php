<?php

namespace Pars\Admin\Config;

use Niceshops\Bean\Type\Base\BeanException;
use Pars\Admin\Base\BaseDetail;

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
