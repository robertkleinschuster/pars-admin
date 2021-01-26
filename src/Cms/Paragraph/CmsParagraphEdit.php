<?php


namespace Pars\Admin\Cms\Paragraph;


use Pars\Admin\Article\ArticleEdit;

class CmsParagraphEdit extends ArticleEdit
{
    private ?array $typeOptions = null;
    private ?array $stateOptions = null;


    protected function initFields()
    {
        parent::initFields();
        if (!$this->textOnly && !$this->translationOnly) {
            if ($this->hasTypeOptions()) {
                $this->getForm()->addSelect('CmsParagraphType_Code', $this->getTypeOptions(), '{CmsParagraphType_Code}', $this->translate('cmsparagraphtype.code'), 2, 1);
            }
            if ($this->hasStateOptions()) {
                $this->getForm()->addSelect('CmsParagraphState_Code', $this->getStateOptions(), '{CmsParagraphState_Code}', $this->translate('cmsparagraphstate.code'), 2, 2);
            }
        }
    }


    protected function getRedirectController(): string
    {
        return 'cmsparagraph';
    }

    protected function getRedirectIdFields(): array
    {
        return [
            'CmsParagraph_ID'
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
