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
        parent::initFields();
        $span = new Badge('{CmsPageState_Code}');
        $span->setLabel($this->translate('cmspagestate.code'));
        $span->setFormat(new CmsPageStateFieldFormat($this->getTranslator()));
        $span->setGroup($this->translate('article.group.visibility'));
        $this->pushField($span);
        $span = new Span('{CmsPageType_Code}', $this->translate('cmspagetype.code'));
        $span->setFormat(new CmsPageTypeFieldFormat($this->getTranslator()));
        $span->setGroup($this->translate('article.group.general'));
        $this->pushField($span);
        $span = new Span('{CmsPageLayout_Code}', $this->translate('cmspagelayout.code'));
        $span->setFormat(new CmsPageLayoutFieldFormat($this->getTranslator()));
        $span->setGroup($this->translate('article.group.general'));
        $this->pushField($span);

    }

    protected function initEditTextButton()
    {
        parent::initEditTextButton();
        $this->getToolbar()->push(
            (new DownloadButton())
                ->addOption('noajax')
                ->setPath(
                    $this->getPathHelper(false)
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
