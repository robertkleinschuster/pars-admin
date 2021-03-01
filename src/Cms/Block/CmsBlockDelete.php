<?php


namespace Pars\Admin\Cms\Block;


use Pars\Admin\Article\ArticleDelete;

class CmsBlockDelete extends ArticleDelete
{
    protected function getRedirectController(): string
    {
        return 'cmsblock';
    }

}
