<?php


namespace Pars\Admin\Cms\PageParagraph;


use Pars\Admin\Cms\Page\CmsPageDelete;
use Pars\Admin\Cms\Paragraph\CmsParagraphDelete;
use Pars\Admin\Cms\Paragraph\CmsParagraphDetail;

class CmsPageParagraphDelete extends CmsParagraphDelete
{
    protected function getRedirectController(): string
    {
        return 'cmspage';
    }

    protected function getRedirectIdFields(): array
    {
        return [
            'CmsPage_ID'
        ];
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

}
