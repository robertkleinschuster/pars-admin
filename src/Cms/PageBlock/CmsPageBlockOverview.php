<?php


namespace Pars\Admin\Cms\PageBlock;


use Pars\Admin\Cms\Block\CmsBlockOverview;
use Pars\Helper\Parameter\IdParameter;

class CmsPageBlockOverview extends CmsBlockOverview
{
    protected function initialize()
    {
        $this->setShowEdit(false);
        $this->setShowMove(true);
        $this->setShowCreateNew(true);
        parent::initialize();
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
