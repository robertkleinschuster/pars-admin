<?php


namespace Pars\Admin\Cms\Menu;


use Pars\Admin\Base\BaseDelete;

class CmsMenuDelete extends BaseDelete
{
    protected function getRedirectController(): string
    {
       return 'cmsmenu';
    }
}
