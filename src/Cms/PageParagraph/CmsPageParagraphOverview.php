<?php


namespace Pars\Admin\Cms\PageParagraph;


use Pars\Admin\Cms\Paragraph\CmsParagraphOverview;

class CmsPageParagraphOverview extends CmsParagraphOverview
{
    protected function initialize()
    {
        $this->setShowEdit(false);
        $this->setShowMove(true);

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

    protected function getMoveRedirectController(): string
    {
        return 'cmspage';
    }

    protected function getMoveRedirectAction(): string
    {
        return 'detail';
    }

    protected function getMoveRedirectIdFields(): array
    {
        return [
            'CmsPage_ID'
        ];
    }


}
