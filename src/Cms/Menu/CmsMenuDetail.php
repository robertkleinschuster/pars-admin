<?php


namespace Pars\Admin\Cms\Menu;


use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;
use Pars\Component\Base\Toolbar\PreviewButton;

class CmsMenuDetail extends BaseDetail
{
    protected ?string $previewPath = null;

    protected bool $showType = true;

    protected function initialize()
    {
        $this->setSection($this->translate('section.menu'));

        $span = new Badge('{CmsMenuState_Code}');
        $span->setLabel($this->translate('cmsmenustate.code'));
        $span->setFormat(new CmsMenuStateFieldFormat($this->getTranslator()));
        $this->append($span);
        if ($this->isShowType()){
            $span = new Span('{CmsMenuType_Code}', $this->translate('cmsmenutype.code'));
            $span->setFormat(new CmsMenuTypeFieldFormat($this->getTranslator()));
            $this->append($span);
        }

        $this->setHeading('{ArticleTranslation_Name}');
        $this->addField('Article_Code', $this->translate('article.code'));
        $this->addField('ArticleTranslation_Code', $this->translate('articletranslation.code'));
        parent::initialize();
        if ($this->hasPreviewPath()) {
            $this->getToolbar()->push((new PreviewButton($this->getPreviewPath()))->setTarget('_blank'));
        }
    }

    protected function getIndexController(): string
    {
        return 'cmsmenu';
    }

    protected function getEditIdFields(): array
    {
        return [
            'CmsMenu_ID'
        ];
    }

    /**
     * @return bool
     */
    public function isShowType(): bool
    {
        return $this->showType;
    }

    /**
     * @param bool $showType
     */
    public function setShowType(bool $showType): void
    {
        $this->showType = $showType;
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
