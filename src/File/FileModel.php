<?php

namespace Pars\Admin\File;

use Pars\Admin\Base\CrudModel;
use Pars\Core\Container\ParsContainer;
use Pars\Model\File\Directory\FileDirectoryBeanFinder;
use Pars\Model\File\FileBean;
use Pars\Model\File\FileBeanFinder;
use Pars\Model\File\FileBeanList;
use Pars\Model\File\FileBeanProcessor;
use Pars\Model\File\Type\FileTypeBeanFinder;

/**
 * Class FileModel
 * @package Pars\Admin\File
 * @method FileBeanFinder getBeanFinder()
 * @method FileBeanProcessor getBeanProcessor()
 * @method FileBean getBean()
 * @method FileBean getEmptyBean(array $data = [])
 * @method FileBeanList getBeanList()
 */
class FileModel extends CrudModel
{
    /**
     *
     */
    public function initialize()
    {
        $this->setBeanFinder(new FileBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new FileBeanProcessor($this->getParsContainer()));
    }

    /**
     * @return array
     */
    public function getFileType_Options(): array
    {
        $finder = new FileTypeBeanFinder($this->getDbAdpater());
        $finder->setFileType_Active(true);
        return $finder->getBeanList()->getSelectOptions();
    }

    /**
     * @return array
     */
    public function getFileDirectory_Options(): array
    {
        $finder = new FileDirectoryBeanFinder($this->getDbAdpater());
        $finder->setFileDirectory_Active(true);
        return $finder->getBeanList()->getSelectOptions();
    }
}
