<?php


namespace Pars\Admin\Cms\Menu;

class CmsSubMenuEdit extends CmsMenuEdit
{
    protected function getRedirectIdFields(): array
    {
        return [
            'CmsMenu_ID' => '{CmsMenu_ID_Parent}'
        ];
    }

    protected function getRedirectController(): string
    {
        return 'cmsmenu';
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
        return ['CmsMenu_ID' => '{CmsMenu_ID_Parent}'
        ];
    }


}
