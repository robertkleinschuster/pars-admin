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

    protected function initName()
    {
        $this->setName('{ArticleTranslation_Name}');
    }


    protected function initialize()
    {
        $span = $this->addSpan('CmsMenu_Name', $this->translate('cmsmenu.name'));
        $span->setGroup($this->translate('cmsmenu.group.general'));

        $span = new Badge('{CmsMenuState_Code}');
        $span->setLabel($this->translate('cmsmenustate.code'));
        $span->setFormat(new CmsMenuStateFieldFormat($this->getTranslator()));
        $span->setGroup($this->translate('cmsmenu.group.visibility'));
        $this->pushField($span);
        if ($this->isShowType()) {
            $span = new Span('{CmsMenuType_Code}', $this->translate('cmsmenutype.code'));
            $span->setFormat(new CmsMenuTypeFieldFormat($this->getTranslator()));
            $span->setGroup($this->translate('cmsmenu.group.general'));
            $this->pushField($span);
        } else {
            $span = $this->addSpan('CmsMenu_Level', $this->translate('cmsmenu.level'));
            $span->setGroup($this->translate('cmsmenu.group.general'));
        }

        $span = $this->addSpan('Article_Code', $this->translate('article.code'));
        $span->setGroup($this->translate('cmsmenu.group.general'));
        $span = $this->addSpan('ArticleTranslation_Code', $this->translate('articletranslation.code'));
        $span->setGroup($this->translate('cmsmenu.group.general'));
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
