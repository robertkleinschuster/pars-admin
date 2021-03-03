<?php

namespace Pars\Admin\Cms\PageBlock;

use Pars\Admin\Cms\Block\CmsBlockDelete;

class CmsPageBlockDelete extends CmsBlockDelete
{
    protected function getRedirectController(): string
    {
        return 'cmspage';
    }

    protected function getRedirectIdFields(): array
    {
        return [
            'CmsPage_ID'
        ];
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }
}
