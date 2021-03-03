<?php

namespace Pars\Admin\Cms\Menu;

class CmsSubMenuDetail extends CmsMenuDetail
{
    protected function initialize()
    {
        $this->setShowType(false);
        parent::initialize();
    }


    protected function getIndexController(): string
    {
        return 'cmsmenu';
    }

    protected function getEditIdFields(): array
    {
        return [
            'CmsMenu_ID'
        ];
    }

    protected function getEditController(): string
    {
        return 'cmssubmenu';
    }

    protected function getIndexAction(): string
    {
        return 'detail';
    }

    protected function getIndexIdFields(): array
    {
        return [
            'CmsMenu_ID' => '{CmsMenu_ID_Parent}'
        ];
    }
}
