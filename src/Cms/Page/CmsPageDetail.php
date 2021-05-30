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
        $this->addFieldType();
        $this->addFieldLayout();
        $this->addFieldState();
        $this->addFieldCodeInternal();
        $this->addFieldCodeUrl();
        if ($this->hasBean()) {
            $this->initFieldsByType($this->getBean()->get('CmsPageType_Code'));
        }
    }

    /**
     * @param string $type
     */
    protected function initFieldsByType(string $type)
    {
        switch ($type) {
            case 'about':
                $this->initFieldsAbout();
                break;
            case 'blog':
                $this->initFieldsBlog();
                break;
            case 'columns':
                $this->initFieldsColumns();
                break;
            case 'contact':
                $this->initFieldsContact();
                break;
            case 'faq':
                $this->initFieldsFaq();
                break;
            case 'gallery':
                $this->initFieldsGallery();
                break;
            case 'default':
            case 'home':
                $this->initFieldsDefault();
                break;
            case 'poll':
                $this->initFieldsPoll();
                break;
            case 'redirect':
                $this->initFieldsRedirect();
                break;
            case 'tesla':
                $this->initFieldsTesla();
                break;
            case 'tiles':
                $this->initFieldsTiles();
                break;
        }
    }

    protected function initFieldsTiles()
    {
        $this->addFieldName();
        $this->addFieldTitle();
        $this->addFieldKeywords();
        $this->addFieldTeaser($this->translate('cmspage.metatext'));
    }

    protected function initFieldsTesla()
    {
        $this->addFieldName();
        $this->addFieldTitle();
        $this->addFieldKeywords();
        $this->addFieldTeaser($this->translate('cmspage.metatext'));
    }

    protected function initFieldsRedirect()
    {
        $this->addFieldPath($this->translate('cmspage.redirect.path'));
    }

    protected function initFieldsPoll()
    {
        $this->addFieldName();
        $this->addFieldTitle();
        $this->addFieldKeywords();
        $this->addFieldTeaser($this->translate('cmspage.metatext'));
    }

    protected function initFieldsDefault()
    {
        $this->addFieldName();
        $this->addFieldTitle();
        $this->addFieldKeywords();
        $this->addFieldTeaser($this->translate('cmspage.metatext'));
        $this->addFieldText();
        $this->addFieldFooter();
    }

    protected function initFieldsGallery()
    {
        $this->addFieldName();
        $this->addFieldTitle();
        $this->addFieldKeywords();
        $this->addFieldTeaser($this->translate('cmspage.metatext'));
    }

    protected function initFieldsFaq()
    {
        $this->addFieldName();
        $this->addFieldTitle();
        $this->addFieldKeywords();
        $this->addFieldTeaser($this->translate('cmspage.metatext'));
    }

    protected function initFieldsContact()
    {
        $this->addFieldName();
        $this->addFieldTitle();
        $this->addFieldKeywords();
        $this->addFieldTeaser($this->translate('cmspage.metatext'));
        $this->addFieldContactEmail();
    }

    protected function initFieldsColumns()
    {
        $this->addFieldName();
        $this->addFieldTitle();
        $this->addFieldKeywords();
        $this->addFieldTeaser($this->translate('cmspage.metatext'));
    }

    protected function initFieldsBlog()
    {
        $this->addFieldName();
        $this->addFieldTitle();
        $this->addFieldKeywords();
        $this->addFieldTeaser($this->translate('cmspage.metatext'));
    }

    protected function initFieldsAbout()
    {
        $this->addFieldName();
        $this->addFieldTitle();
        $this->addFieldKeywords();
        $this->addFieldTeaser($this->translate('cmspage.metatext'));
    }



    protected function addFieldLayout()
    {
        $span = new Span('{CmsPageLayout_Code}', $this->translate('cmspagelayout.code'));
        $span->setFormat(new CmsPageLayoutFieldFormat($this->getTranslator()));
        $span->setGroup($this->getGroupGeneral());
        $this->pushField($span);
        return $span;
    }

    protected function addFieldType()
    {
        $span = new Span('{CmsPageType_Code}', $this->translate('cmspagetype.code'));
        $span->setFormat(new CmsPageTypeFieldFormat($this->getTranslator()));
        $span->setGroup($this->getGroupGeneral());
        $this->pushField($span);
        return $span;
    }

    protected function addFieldState()
    {
        $span = new Badge('{CmsPageState_Code}');
        $span->setLabel($this->translate('cmspagestate.code'));
        $span->setFormat(new CmsPageStateFieldFormat($this->getTranslator()));
        $span->setGroup($this->getGroupGeneral());
        $this->pushField($span);
        return $span;
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
            ->setTooltip($this->translate('download'))
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
