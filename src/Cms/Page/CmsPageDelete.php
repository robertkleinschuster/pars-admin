<?php

namespace Pars\Admin\Cms\Page;

use Pars\Admin\Article\ArticleDelete;

class CmsPageDelete extends ArticleDelete
{
    protected function getRedirectController(): string
    {
        return 'cmspage';
    }
}
