<?php


namespace Pars\Admin\File;


use Pars\Admin\Base\BaseDelete;

class FileDelete extends BaseDelete
{
    protected function getRedirectController(): string
    {
        return 'file';
    }

}
