<?php


namespace Pars\Admin\Cms\Block;


class CmsSubBlockDetail extends CmsBlockDetail
{

    protected function getIndexController(): string
    {
        return 'cmsblock';
    }

    protected function getEditIdFields(): array
    {
        return [
            'CmsBlock_ID'
        ];
    }

    protected function getEditController(): string
    {
        return 'cmssubblock';
    }

    protected function getIndexAction(): string
    {
        return 'detail';
    }

    protected function getIndexIdFields(): array
    {
        return [
            'CmsBlock_ID' => '{CmsBlock_ID_Parent}'
        ];
    }
}
