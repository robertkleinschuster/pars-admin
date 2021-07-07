<?php


namespace Pars\Admin\Picture;


use Pars\Admin\Base\ContentNavigation;
use Pars\Admin\File\FileController;

class PictureController extends FileController
{
    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('content');
        $subNavigation = new ContentNavigation($this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('picture');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

}
