<?php

namespace Pars\Admin\Cms\Page;

use Pars\Admin\Article\ArticleDetail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;
use Pars\Component\Base\Toolbar\DownloadButton;

class CmsPageDetail extends ArticleDetail
{


    protected function initFields()
    {
        $span = new Badge('{CmsPageState_Code}');
        $span->setLabel($this->translate('cmspagestate.code'));
        $span->setFormat(new CmsPageStateFieldFormat($this->getTranslator()));
        #$this->append($span, 1, 3);
        $span = new Span('{CmsPageType_Code}', $this->translate('cmspagetype.code'));
        $span->setFormat(new CmsPageTypeFieldFormat($this->getTranslator()));
        #$this->append($span, 2, 3);
        $span = new Span('{CmsPageLayout_Code}', $this->translate('cmspagelayout.code'));
        $span->setFormat(new CmsPageLayoutFieldFormat($this->getTranslator()));
        #$this->append($span, 3, 3);
        parent::initFields();

    }

    protected function initEditTextButton()
    {
        parent::initEditTextButton();
        $this->getToolbar()->push(
            (new DownloadButton())
                ->addOption('noajax')
                ->setPath(
                    $this->getPathHelper(true)
                        ->setAction('export')
                        ->getPath()
                )
        );
    }


    protected function getIndexController(): string
    {
        return 'cmspage';
    }

    protected function getEditIdFields(): array
    {
        return [
            'CmsPage_ID'
        ];
    }
}
