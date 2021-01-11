<?php


namespace Pars\Admin\Cms\Post;


use Pars\Admin\Article\ArticleEdit;
use Pars\Admin\Base\ValueWarningFieldFormat;

class CmsPostEdit extends ArticleEdit
{

    protected ?array $stateOptions = null;
    protected ?array $typeOptions = null;

    protected function initialize()
    {
        if ($this->hasTypeOptions()) {
            $this->getForm()->addSelect('CmsPostType_Code', $this->getTypeOptions(), '{CmsPostType_Code}', $this->translate('cmsposttype.code'), 2, 2);
        }
        if ($this->hasStateOptions()) {
            $this->getForm()->addSelect('CmsPostState_Code', $this->getStateOptions(), '{CmsPostState_Code}', $this->translate('cmspoststate.code'), 2, 3)
                ->setFormat(new ValueWarningFieldFormat('CmsPostState_Code', 'inactive'));
        }
        $this->getForm()->addDateTime('CmsPost_PublishTimestamp', '{CmsPost_PublishTimestamp}', $this->translate('cmspost.publishtimestamp'), 3, 1);
        parent::initialize();
    }


    protected function getRedirectController(): string
    {
        return 'cmspage';
    }

    protected function getRedirectIdFields(): array
    {
        return ['CmsPage_ID'];
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

    protected function getCreateRedirectAction(): string
    {
        return 'detail';
    }

    protected function getCreateRedirectIdFields(): array
    {
        return ['CmsPage_ID'];
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


}
