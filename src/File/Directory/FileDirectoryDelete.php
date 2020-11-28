<?php


namespace Pars\Admin\File\Directory;


use Pars\Admin\Base\BaseDelete;

class FileDirectoryDelete extends BaseDelete
{
    protected function getRedirectController(): string
    {
       return 'filedirectory';
    }

}
