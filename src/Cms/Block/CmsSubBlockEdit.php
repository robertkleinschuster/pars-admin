<?php


namespace Pars\Admin\Cms\Block;


class CmsSubBlockEdit extends CmsBlockEdit
{
    protected function getRedirectIdFields(): array
    {
        return [
            'CmsBlock_ID' => '{CmsBlock_ID_Parent}'
        ];
    }

    protected function getRedirectController(): string
    {
        return 'cmsblock';
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

    protected function getCreateRedirectAction(): string
    {
        return 'detail';
    }

    protected function getCreateRedirectIdFields(): array
    {
        return ['CmsBlock_ID' => '{CmsBlock_ID_Parent}'
        ];
    }
}
