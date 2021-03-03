<?php

namespace Pars\Admin\Cms\PageBlock;

use Pars\Admin\Cms\Block\CmsBlockEdit;

class CmsPageBlockCreateNew extends CmsBlockEdit
{
    protected ?array $blockOptions = null;


    protected function initFields()
    {
        $this->setCreate(true);
        parent::initFields();
    }


    /**
     * @return array
     */
    public function getBlockOptions(): array
    {
        return $this->blockOptions;
    }

    /**
     * @param array $blockOptions
     *
     * @return $this
     */
    public function setBlockOptions(array $blockOptions): self
    {
        $this->blockOptions = $blockOptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasBlockOptions(): bool
    {
        return isset($this->blockOptions);
    }

    protected function getRedirectController(): string
    {
        return 'cmspage';
    }

    protected function getCreateRedirectAction(): string
    {
        return 'detail';
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

    protected function getCreateRedirectIdFields(): array
    {
        return [
            'CmsPage_ID'
        ];
    }
}
