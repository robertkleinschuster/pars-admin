<?php


namespace Pars\Admin\Cms\Page;


use Pars\Admin\Article\ArticleEdit;
use Pars\Admin\Base\NotEmptyWarningFieldFormat;
use Pars\Admin\Base\ValueWarningFieldFormat;
use Pars\Mvc\View\HtmlElement;

class CmsPageEdit extends ArticleEdit
{
    protected ?array $pageStateOptions = null;
    protected ?array $pageTypeOptions = null;
    protected ?array $pageRedirectOptions = null;
    protected ?array $pageLayoutOptions = null;

    protected function initialize()
    {
        parent::initialize();
        if (!$this->textOnly && !$this->translationOnly) {
            if ($this->hasLayoutOptions()) {
                $this->getForm()->addSelect('CmsPageLayout_Code', $this->getLayoutOptions(), '{CmsPageLayout_Code}', $this->translate('cmspagelayout.code'), 10, 1);
            }
            if ($this->hasTypeOptions()) {
                $this->getForm()->addSelect('CmsPageType_Code', $this->getTypeOptions(), '{CmsPageType_Code}', $this->translate('cmspagetype.code'), 10, 2)->addOption('ajax');
            }
            if ($this->hasStateOptions()) {
                $this->getForm()->addSelect('CmsPageState_Code', $this->getStateOptions(), '{CmsPageState_Code}', $this->translate('cmspagestate.code'), 10, 3)
                    ->setFormat(new ValueWarningFieldFormat('CmsPageState_Code', 'inactive'));
            }
            if ($this->hasRedirectOptions()) {
                $this->getForm()->addSelect('CmsPage_ID_Redirect', $this->getRedirectOptions(), '{CmsPage_ID_Redirect}', $this->translate('cmspage.id.redirect'), 10, 4)
                    ->setFormat(new NotEmptyWarningFieldFormat('CmsPage_ID_Redirect'));
            }
            if ($this->getBean()->get('CmsPageType_Code') == 'poll') {
                $this->getForm()->addCheckbox('Article_Data[vote_once]', '{Article_Data[vote_once]}', $this->translate('article.data.vote.once')
                    , 11, 1);
            }
            if ($this->getBean()->get('CmsPageType_Code') == 'contact') {
                $this->getForm()->addEmail('Article_Data[contact_email]', '{Article_Data[contact_email]}', $this->translate('article.data.contact_email')
                    , 11, 1)->getInput()->setRequired(true);
            }
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

}
