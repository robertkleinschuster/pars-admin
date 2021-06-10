<?php

namespace Pars\Admin\Cms\Block;

use Pars\Admin\Article\ArticleEdit;

class CmsBlockEdit extends ArticleEdit
{
    private ?array $typeOptions = null;
    private ?array $stateOptions = null;

    protected function initFields()
    {
        parent::initFields();
        $this->addFieldType();
        $this->addFieldState();
        $this->addFieldCodeInternal();
        $this->addFieldCodeUrl();
        $this->addFieldName();
        if ($this->hasBean()) {
            $this->initFieldsByType($this->getBean()->get('CmsBlockType_Code'));
        }
    }


    protected function addFieldType()
    {
        if ($this->hasTypeOptions()) {
            $this->getForm()->addSelect(
                'CmsBlockType_Code',
                $this->getTypeOptions(),
                '{CmsBlockType_Code}',
                $this->translate('cmsblocktype.code'),
            )->setGroup($this->getGroupGeneral());
        }
    }

    protected function addFieldState()
    {
        if ($this->hasStateOptions()) {
            $this->getForm()->addSelect(
                'CmsBlockState_Code',
                $this->getStateOptions(),
                '{CmsBlockState_Code}',
                $this->translate('cmsblockstate.code'),
            )->setGroup($this->getGroupGeneral());
        }
    }

    protected function initFieldsByType(string $type)
    {
        switch ($type) {
            case 'banner':
                $this->initFieldsBanner();
                break;
            case 'card':
                $this->initFieldsCard();
                break;
            case 'contact':
                $this->initFieldsContact();
                break;
            case 'link':
                $this->initFieldsLink();
                break;
            case 'picture':
                $this->initFieldsPicture();
                break;
            case 'poll':
                $this->initFieldsPoll();
                break;
            case 'text':
                $this->initFieldsText();
                break;
            case 'tiles':
                $this->initFieldsTiles();
                break;
            case 'video':
                $this->initFieldsVideo();
                break;
            case 'default':
                $this->initFieldsDefault();
                break;
        }
    }

    protected function initFieldsBanner()
    {
        $this->addFieldPath($this->translate('cmsblock.banner.path'));
        $this->addFieldHeading($this->translate('cmsblock.banner.heading'));
        $this->addFieldSubHeading($this->translate('cmsblock.banner.subheading'));
        $this->addFieldText($this->translate('cmsblock.banner.text'));
    }
    protected function initFieldsLink()
    {
        $this->addFieldPath($this->translate('cmsblock.link.path'));
        $this->addFieldHeading($this->translate('cmsblock.link.heading'));
    }

    protected function initFieldsPicture()
    {

    }

    protected function initFieldsPoll()
    {
        $this->addFieldHeading($this->translate('cmsblock.poll.heading'));
        $this->addFieldText($this->translate('cmsblock.poll.text'));
    }

    protected function initFieldsTiles()
    {
        $this->addFieldHeading($this->translate('cmsblock.tiles.heading'));
    }

    protected function initFieldsVideo()
    {
        $this->addFieldPath($this->translate('cmsblock.video.path'));
    }

    protected function initFieldsDefault()
    {
        $this->addFieldText($this->translate('cmsblock.default.text'));
    }

    protected function initFieldsText()
    {
        $this->addFieldText($this->translate('cmsblock.text.text'));
    }

    protected function initFieldsContact()
    {
        $this->addFieldContactEmail();
    }

    protected function initFieldsCard()
    {
        $this->addFieldHeading();
        $this->addFieldText();
        $this->addFieldPath();
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
