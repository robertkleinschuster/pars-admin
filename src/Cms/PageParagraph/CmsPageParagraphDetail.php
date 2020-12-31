<?php


namespace Pars\Admin\Cms\PageParagraph;


use Pars\Admin\Cms\Paragraph\CmsParagraphDetail;

class CmsPageParagraphDetail extends CmsParagraphDetail
{
    protected function initialize()
    {
        parent::initialize();
    }

    protected function getEditIdFields(): array
    {
        return [
            'CmsPage_ID',
            'CmsParagraph_ID'
        ];
    }

    protected function getEditController(): string
    {
        return 'cmsparagraph';
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
