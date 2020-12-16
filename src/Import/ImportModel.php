<?php

namespace Pars\Admin\Import;

use Pars\Admin\Base\CrudModel;
use Pars\Model\Import\ImportBeanFinder;
use Pars\Model\Import\ImportBeanProcessor;
use Pars\Model\Import\Type\ImportTypeBeanFinder;

/**
 * Class ImportModel
 * @package Pars\Admin\Import
 */
class ImportModel extends CrudModel
{
    public function initialize()
    {
        parent::initialize();
        $this->setBeanProcessor(new ImportBeanProcessor($this->getDbAdpater()));
        $this->setBeanFinder(new ImportBeanFinder($this->getDbAdpater()));
    }

    /**
     * @return array
     * @throws \Niceshops\Bean\Type\Base\BeanException
     */
    public function getImportTypeOptions(): array
    {
        $finder = new ImportTypeBeanFinder($this->getDbAdpater());
        $finder->setImportType_Active(true);
        $options = [];
        foreach ($finder->getBeanListDecorator() as $item) {
            $options[$item->get('ImportType_Code')] = $this->translate('importtype.code.' . $item->get('ImportType_Code'));
        }
        return $options;
    }

}
