<?php


namespace Pars\Admin\Cms\Page;


use Pars\Admin\Article\ArticleDetail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;
use Pars\Component\Base\Toolbar\PreviewButton;

class CmsPageDetail extends ArticleDetail
{
    protected ?string $previewPath = null;

    protected function initialize()
    {
        $this->setSection($this->translate('section.page'));
        $span = new Badge('{CmsPageState_Code}');
        $span->setLabel($this->translate('cmspagestate.code'));
        $span->setFormat(new CmsPageStateFieldFormat($this->getTranslator()));
        $this->append($span);
        $span = new Span('{CmsPageType_Code}', $this->translate('cmspagetype.code'));
        $span->setFormat(new CmsPageTypeFieldFormat($this->getTranslator()));
        $this->append($span);
        $span = new Span('{CmsPageLayout_Code}', $this->translate('cmspagelayout.code'));
        $span->setFormat(new CmsPageLayoutFieldFormat($this->getTranslator()));
        $this->append($span);
        parent::initialize();
        if ($this->hasPreviewPath()) {
            $this->getToolbar()->push((new PreviewButton($this->getPreviewPath()))->setTarget('_blank'));
        }
    }


    protected function getIndexController(): string
    {
        return 'cmspage';
    }

    protected function getEditIdFields(): array
    {
        return [
            'CmsPage_ID'
        ];
    }

    /**
    * @return string
    */
    public function getPreviewPath(): string
    {
        return $this->previewPath;
    }

    /**
    * @param string $previewPath
    *
    * @return $this
    */
    public function setPreviewPath(string $previewPath): self
    {
        $this->previewPath = $previewPath;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasPreviewPath(): bool
    {
        return isset($this->previewPath);
    }


}
