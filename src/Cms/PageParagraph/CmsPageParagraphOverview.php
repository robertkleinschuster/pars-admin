<?php


namespace Pars\Admin\Cms\PageParagraph;


use Pars\Admin\Cms\Paragraph\CmsParagraphOverview;
use Pars\Helper\Parameter\IdParameter;

class CmsPageParagraphOverview extends CmsParagraphOverview
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
        return 'cmspageparagraph';
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
            'CmsParagraph_ID'
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
