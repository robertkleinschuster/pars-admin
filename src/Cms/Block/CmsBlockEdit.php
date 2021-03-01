<?php


namespace Pars\Admin\Cms\Block;


use Pars\Admin\Article\ArticleEdit;

class CmsBlockEdit extends ArticleEdit
{
    private ?array $typeOptions = null;
    private ?array $stateOptions = null;

    protected function initAttributeFields()
    {
        parent::initAttributeFields();
        if (!$this->isTranslationOnly() && !$this->isTextOnly()) {
            if ($this->hasTypeOptions()) {
                $this->getForm()->addSelect(
                    'CmsBlockType_Code',
                    $this->getTypeOptions(),
                    '{CmsBlockType_Code}',
                    $this->translate('cmsblocktype.code'),
                    2,
                    1
                );
            }
            if ($this->hasStateOptions()) {
                $this->getForm()->addSelect(
                    'CmsBlockState_Code',
                    $this->getStateOptions(),
                    '{CmsBlockState_Code}',
                    $this->translate('cmsblockstate.code'),
                    2,
                    2
                );
            }
        }
    }


    protected function getRedirectController(): string
    {
        return 'cmsblock';
    }

    protected function getRedirectAction(): string
    {
        return 'index';
    }

    protected function getRedirectIdFields(): array
    {
        return [
            'CmsBlock_ID'
        ];
    }

    /**
     * @return array
     */
    public function getTypeOptions(): array
    {
        return $this->typeOptions;
    }

    /**
     * @param array $typeOptions
     *
     * @return $this
     */
    public function setTypeOptions(array $typeOptions): self
    {
        $this->typeOptions = $typeOptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasTypeOptions(): bool
    {
        return isset($this->typeOptions);
    }

    /**
     * @return array
     */
    public function getStateOptions(): array
    {
        return $this->stateOptions;
    }

    /**
     * @param array $stateOptions
     *
     * @return $this
     */
    public function setStateOptions(array $stateOptions): self
    {
        $this->stateOptions = $stateOptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasStateOptions(): bool
    {
        return isset($this->stateOptions);
    }


}
