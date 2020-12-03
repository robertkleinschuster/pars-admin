<?php


namespace Pars\Admin\Cms\PageParagraph;

use Pars\Admin\Cms\Paragraph\CmsParagraphEdit;

class CmsPageParagraphEdit extends CmsParagraphEdit
{
    protected ?array $paragraphOptions = null;


    protected function initFields()
    {
       $this->setCreateBulk(true);
       $this->setCreate(false);
    }


    /**
    * @return array
    */
    public function getParagraphOptions(): array
    {
        return $this->paragraphOptions;
    }

    /**
    * @param array $paragraphOptions
    *
    * @return $this
    */
    public function setParagraphOptions(array $paragraphOptions): self
    {
        $this->paragraphOptions = $paragraphOptions;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasParagraphOptions(): bool
    {
        return isset($this->paragraphOptions);
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
