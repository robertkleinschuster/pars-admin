<?php

namespace Pars\Admin\Cms\PageBlock;

use Pars\Admin\Cms\Block\CmsBlockDetail;

class CmsPageBlockDetail extends CmsBlockDetail
{
    protected function initialize()
    {
        parent::initialize();
    }

    protected function getEditIdFields(): array
    {
        return [
            'CmsPage_ID',
            'CmsBlock_ID'
        ];
    }

    protected function getEditController(): string
    {
        return 'cmsblock';
    }

    protected function getEditAction(): string
    {
        return 'edit';
    }


    protected function getIndexController(): string
    {
        return 'cmspage';
    }

    protected function getIndexAction(): string
    {
        return 'detail';
    }

    protected function getIndexIdFields(): array
    {
        return [
            'CmsPage_ID'
        ];
    }
}
