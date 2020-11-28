<?php


namespace Pars\Admin\Cms\Page;


use Pars\Admin\Article\ArticleEdit;

class CmsPageEdit extends ArticleEdit
{
    protected ?array $pageStateOptions = null;
    protected ?array $pageTypeOptions = null;

    protected function initialize()
    {
        parent::initialize();
        if ($this->hasTypeOptions()) {
            $this->getForm()->addSelect('CmsPageType_Code',$this->getTypeOptions(),'{CmsPageType_Code}' , $this->translate('cmspagetype.code'), 2, 1);
        }
        if ($this->hasStateOptions()) {
            $this->getForm()->addSelect('CmsPageState_Code', $this->getStateOptions(),'{CmsPageState_Code}', $this->translate('cmspagestate.code'), 2,2);
        }
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
