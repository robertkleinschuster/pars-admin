<?php


namespace Pars\Admin\Cms\Menu;


class CmsSubMenuOverview extends CmsMenuOverview
{
    protected function initialize()
    {
        $this->setShowType(false);
        parent::initialize();
    }


    protected function getController(): string
    {
        return 'cmssubmenu';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'CmsMenu_ID'
        ];
    }

    protected function getCreateIdFields(): array
    {
        return [
            'CmsMenu_ID_Parent'
        ];
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

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


}
