<?php


namespace Pars\Admin\Article\Picture;


use Pars\Admin\Picture\PictureController;

/**
 * Class ArticlePictureController
 * @package Pars\Admin\Article\Picture
 * @method ArticlePictureModel getModel()
 */
class ArticlePictureController extends PictureController
{

    public function linkAction()
    {
        $link = new ArticlePictureLink($this->getTranslator(), $this->getUserBean());
        $link->setBeanList($this->getModel()->getPictureBeanList());
        $this->injectContext($link);
        $link->setToken($this->getTokenName(), $this->generateToken($this->getTokenName()));
        $this->getView()->pushComponent($link);
        return $link;
    }
}
