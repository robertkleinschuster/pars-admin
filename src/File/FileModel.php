<?php

namespace Pars\Admin\File;

use Pars\Admin\Base\CrudModel;
use Pars\Model\File\Directory\FileDirectoryBeanFinder;
use Pars\Model\File\FileBeanFinder;
use Pars\Model\File\FileBeanProcessor;
use Pars\Model\File\Type\FileTypeBeanFinder;

class FileModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new FileBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new FileBeanProcessor($this->getDbAdpater()));
    }

    public function getFileType_Options()
    {
        $options = [];
        $finder = new FileTypeBeanFinder($this->getDbAdpater());
        $finder->setFileType_Active(true);

        $beanList = $finder->getBeanList();
        foreach ($beanList as $bean) {
            $options[$bean->get('FileType_Code')] = $bean->get('FileType_Name');
        }
        return $options;
    }

    public function getFileDirectory_Options()
    {
        $options = [];
        $finder = new FileDirectoryBeanFinder($this->getDbAdpater());
        $finder->setFileDirectory_Active(true);

        $beanList = $finder->getBeanList();
        foreach ($beanList as $bean) {
            $options[$bean->get('FileDirectory_ID')] = $bean->get('FileDirectory_Name');
        }
        return $options;
    }
}
