<?php


namespace Pars\Admin\Article\Picture;


use Pars\Admin\Picture\PictureDetail;

class ArticlePictureDetail extends PictureDetail
{
    protected function getEditController(): string
    {
        return 'articlepicture';
    }

}
