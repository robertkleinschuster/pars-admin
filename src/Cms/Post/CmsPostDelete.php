<?php

namespace Pars\Admin\Cms\Post;

use Pars\Admin\Article\ArticleDelete;

class CmsPostDelete extends ArticleDelete
{
    protected function getRedirectController(): string
    {
        return 'cmspage';
    }

    protected function getRedirectIdFields(): array
    {
        return ['CmsPage_ID'];
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }
}
