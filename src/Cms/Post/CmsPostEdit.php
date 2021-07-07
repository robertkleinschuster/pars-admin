<?php

namespace Pars\Admin\Cms\Post;

use Pars\Admin\Article\ArticleEdit;
use Pars\Admin\Base\ValueWarningFieldFormat;
use Pars\Bean\Type\Base\BeanException;

class CmsPostEdit extends ArticleEdit
{

    protected ?array $stateOptions = null;
    protected ?array $typeOptions = null;


    /**
     * @throws BeanException
     */
    protected function initFields()
    {
        parent::initFields();
        $this->addFieldType();
        $this->addFieldState();
        $this->addFieldPublishTimestamp();
        $this->addFieldCodeUrl();
        $this->addFieldCodeInternal();
        $this->addFieldName();
        $this->addFieldTitle();
        $this->addFieldHeading();
        $this->addFieldSubHeading();
        $this->addFieldKeywords();
        $this->addFieldTeaser();
        $this->addFieldText();
        $this->addFieldFooter();
    }

    protected function addFieldPublishTimestamp()
    {
        $formGroup = $this->getForm()->addDateTime('CmsPost_PublishTimestamp', '{CmsPost_PublishTimestamp}', $this->translate('cmspost.publishtimestamp'));
        $formGroup->setGroup($this->getGroupGeneral());
        return $formGroup;
    }

    protected function addFieldType()
    {
        if ($this->hasTypeOptions()) {
            $formGroup = $this->getForm()->addSelect('CmsPostType_Code', $this->getTypeOptions(), '{CmsPostType_Code}', $this->translate('cmsposttype.code'));
            $formGroup->setGroup($this->getGroupGeneral());
        }
    }

    protected function addFieldState()
    {
        if ($this->hasStateOptions()) {
            $formGroup = $this->getForm()->addSelect('CmsPostState_Code', $this->getStateOptions(), '{CmsPostState_Code}', $this->translate('cmspoststate.code'));
            $formGroup->setFormat(new ValueWarningFieldFormat('CmsPostState_Code', 'inactive'));
            $formGroup->setGroup($this->getGroupGeneral());
        }
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
