<?php

namespace Pars\Admin\File\Directory;

use Pars\Admin\Base\CrudModel;
use Pars\Model\File\Directory\FileDirectoryBeanFinder;
use Pars\Model\File\Directory\FileDirectoryBeanProcessor;

class FileDirectoryModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new FileDirectoryBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new FileDirectoryBeanProcessor($this->getDbAdpater()));
    }
}
