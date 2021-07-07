<?php

namespace Pars\Admin\Cms\PageBlock;

use Pars\Admin\Cms\Block\CmsBlockOverview;

/**
 * Class CmsPageBlockOverview
 * @package Pars\Admin\Cms\PageBlock
 */
class CmsPageBlockOverview extends CmsBlockOverview
{
    protected function initBase()
    {
        parent::initBase();
        $this->setShowEdit(false);
        $this->setShowMove(true);
        $this->setShowCreateNew(true);
        $this->setShowOrder(false);
    }


    protected function getController(): string
    {
        return 'cmspageblock';
    }


    protected function getCreateIdFields(): array
    {
        return [
            'CmsPage_ID'
        ];
    }

    protected function getDetailIdFields(): array
    {
        return [
            'CmsPage_ID',
            'CmsBlock_ID'
        ];
    }

    protected function getRedirectController(): string
    {
        return 'cmspage';
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

    protected function getRedirectIdFields(): array
    {
        return [
            'CmsPage_ID'
        ];
    }
}
