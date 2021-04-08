<?php

namespace Pars\Admin\Config;

use Pars\Admin\Base\CrudModel;
use Pars\Model\Config\ConfigBeanFinder;
use Pars\Model\Config\ConfigBeanProcessor;
use Pars\Model\Config\Type\ConfigTypeBeanFinder;

/**
 * Class ConfigModel
 * @package Pars\Admin\Config
 */
class ConfigModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanProcessor(new ConfigBeanProcessor($this->getDbAdpater()));
        $this->setBeanFinder(new ConfigBeanFinder($this->getDbAdpater()));
    }

    /**
     * @return array
     * @throws \Pars\Bean\Type\Base\BeanException
     */
    public function getConfigType_Options(bool $emptyElement = false): array
    {
        $result = [];
        if ($emptyElement) {
            $result[] = $this->translate('noselection');
        }
        $finder = new ConfigTypeBeanFinder($this->getDbAdpater());
        foreach ($finder->getBeanListDecorator() as $bean) {
            $result[$bean->get('ConfigType_Code')] = $bean->get('ConfigType_Code');
        }
        return $result;
    }
}
