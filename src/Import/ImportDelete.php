<?php


namespace Pars\Admin\Import;


use Pars\Admin\Base\BaseDelete;

class ImportDelete extends BaseDelete
{
    protected function getRedirectController(): string
    {
        return 'import';
    }

}
