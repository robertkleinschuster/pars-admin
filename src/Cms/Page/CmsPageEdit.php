<?php

namespace Pars\Admin\Cms\Page;

use Pars\Admin\Article\ArticleEdit;
use Pars\Admin\Base\NotEmptyWarningFieldFormat;
use Pars\Admin\Base\ValueWarningFieldFormat;
use Pars\Component\Base\Form\Wysiwyg\Action;

/**
 * Class CmsPageEdit
 * @package Pars\Admin\Cms\Page
 */
class CmsPageEdit extends ArticleEdit
{
    protected ?array $pageStateOptions = null;
    protected ?array $pageTypeOptions = null;
    protected ?array $pageRedirectOptions = null;
    protected ?array $pageLayoutOptions = null;

    protected function initFields()
    {
        parent::initFields();
        $this->getForm()->addHidden('CmsPage_ID_Redirect', '0');
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
        $this->addFieldRedirect();
        $this->addFieldPath($this->translate('cmspage.redirect.path'));
    }

    protected function initFieldsPoll()
    {
        $this->addFieldName();
        $this->addFieldTitle();
        $this->addFieldKeywords();
        $this->addFieldTeaser($this->translate('cmspage.metatext'));
        $this->addFieldVoteOnce();
    }

    protected function initFieldsDefault()
    {
        $this->addFieldName();
        $this->addFieldText();
        $this->addFieldFooter();
        $this->addFieldTitle();
        $this->addFieldKeywords();
        $this->addFieldTeaser($this->translate('cmspage.metatext'));
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


    protected function addFieldType()
    {
        if ($this->hasTypeOptions()) {
            $this->getForm()->addSelect('CmsPageType_Code', $this->getTypeOptions(), '{CmsPageType_Code}', $this->translate('cmspagetype.code'))->addOption('ajax')
                ->setGroup($this->getGroupGeneral());
        }
    }

    protected function addFieldState()
    {
        if ($this->hasStateOptions()) {
            $this->getForm()->addSelect('CmsPageState_Code', $this->getStateOptions(), '{CmsPageState_Code}', $this->translate('cmspagestate.code'))
                ->setFormat(new ValueWarningFieldFormat('CmsPageState_Code', 'inactive'))
                ->setGroup($this->getGroupGeneral());
        }
    }

    protected function addFieldLayout()
    {
        if ($this->hasLayoutOptions()) {
            $this->getForm()->addSelect('CmsPageLayout_Code', $this->getLayoutOptions(), '{CmsPageLayout_Code}', $this->translate('cmspagelayout.code'))
                ->setGroup($this->getGroupGeneral());
        }
    }

    protected function addFieldRedirect()
    {
        if ($this->hasRedirectOptions()) {
            $this->getForm()->addSelect('CmsPage_ID_Redirect', $this->getRedirectOptions(), '{CmsPage_ID_Redirect}', $this->translate('cmspage.id.redirect'))
                ->setFormat(new NotEmptyWarningFieldFormat('CmsPage_ID_Redirect'))
                ->setGroup($this->getGroupAdditional());
        }
    }



    /**
     * @return array
     */
    public function getRedirectOptions(): array
    {
        return $this->pageRedirectOptions;
    }

    /**
     * @param array $pageRedirectOptions
     *
     * @return $this
     */
    public function setRedirectOptions(array $pageRedirectOptions): self
    {
        $this->pageRedirectOptions = $pageRedirectOptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasRedirectOptions(): bool
    {
        return isset($this->pageRedirectOptions);
    }


    /**
     * @return array
     */
    public function getStateOptions(): array
    {
        return $this->pageStateOptions;
    }

    /**
     * @param array $pageStateOptions
     *
     * @return $this
     */
    public function setStateOptions(array $pageStateOptions): self
    {
        $this->pageStateOptions = $pageStateOptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasStateOptions(): bool
    {
        return isset($this->pageStateOptions);
    }

    /**
     * @return array
     */
    public function getTypeOptions(): array
    {
        return $this->pageTypeOptions;
    }

    /**
     * @param array $pageTypeOptions
     *
     * @return $this
     */
    public function setTypeOptions(array $pageTypeOptions): self
    {
        $this->pageTypeOptions = $pageTypeOptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasTypeOptions(): bool
    {
        return isset($this->pageTypeOptions);
    }

    /**
     * @return array
     */
    public function getLayoutOptions(): array
    {
        return $this->pageLayoutOptions;
    }

    /**
     * @param array $pageLayoutOptions
     *
     * @return $this
     */
    public function setLayoutOptions(array $pageLayoutOptions): self
    {
        $this->pageLayoutOptions = $pageLayoutOptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasLayoutOptions(): bool
    {
        return isset($this->pageLayoutOptions);
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

    protected function getRedirectController(): string
    {
        return 'cmspage';
    }
}
