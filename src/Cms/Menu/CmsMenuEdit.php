<?php

namespace Pars\Admin\Cms\Menu;

use Pars\Admin\Base\BaseEdit;

class CmsMenuEdit extends BaseEdit
{

    protected ?array $pageOptions = null;
    protected ?array $stateOptions = null;
    protected ?array $typeOptions = null;

    protected function initialize()
    {
        if ($this->hasPageOptions()) {
            $this->getForm()->addSelect('CmsPage_ID', $this->getPageOptions(), '{CmsPage_ID}', $this->translate('cmspage.id'));
        }
        if ($this->hasTypeOptions()) {
            $this->getForm()->addSelect('CmsMenuType_Code', $this->getTypeOptions(), '{CmsMenuType_Code}', $this->translate('cmsmenutype.code'));
        }
        if ($this->hasStateOptions()) {
            $this->getForm()->addSelect('CmsMenuState_Code', $this->getStateOptions(), '{CmsMenuState_Code}', $this->translate('cmsmenustate.code'));
        }

        parent::initialize();
    }

    protected function getRedirectController(): string
    {
        return 'cmsmenu';
    }

    protected function getRedirectAction(): string
    {
        return 'index';
    }

    protected function getRedirectIdFields(): array
    {
        return [];
    }


    /**
     * @return array
     */
    public function getPageOptions(): array
    {
        return $this->pageOptions;
    }

    /**
     * @param array $pageOptions
     *
     * @return $this
     */
    public function setPageOptions(array $pageOptions): self
    {
        $this->pageOptions = $pageOptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasPageOptions(): bool
    {
        return isset($this->pageOptions);
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
