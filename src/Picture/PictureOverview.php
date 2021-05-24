<?php


namespace Pars\Admin\Picture;


use Pars\Admin\File\FileOverview;

class PictureOverview extends FileOverview
{
    protected function getDetailIdFields(): array
    {
        return [
            'Picture_ID'
        ];
    }


}
