<?php


namespace Pars\Admin\Article\Picture;


use Pars\Admin\Picture\PictureDetail;

class ArticlePictureDetail extends PictureDetail
{
    protected function getEditController(): string
    {
        return $this->getPathHelper(false)->getController();
    }

    protected function getEditIdFields(): array
    {
        return $this->getPathHelper(false)->getId()->getAttribute_Keys();
    }
}
