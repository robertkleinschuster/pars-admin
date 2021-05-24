<?php


namespace Pars\Admin\Picture;


use Pars\Admin\File\FileModel;
use Pars\Model\Picture\PictureBeanFinder;
use Pars\Model\Picture\PictureBeanProcessor;

class PictureModel extends FileModel
{
    /**
     *
     */
    public function initialize()
    {
        $this->setBeanFinder(new PictureBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new PictureBeanProcessor($this->getParsContainer()));
    }
}
