<?php

namespace Pars\Admin\Cms\Page;

use Pars\Admin\Article\ArticleOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Field\Span;
use Pars\Component\Base\Toolbar\DownloadButton;

class CmsPageOverview extends ArticleOverview
{
    protected function initName()
    {
        $this->setName($this->translate('section.page'));
    }


    protected function initialize()
    {
        $this->setShowOrder(true);
        $span = new Badge('{CmsPageState_Code}');
        $span->setFormat(new CmsPageStateFieldFormat($this->getTranslator()));
        $span->setTooltip($this->translate('cmspagestate.code'));
        if ($this->hasDetailPath()) {
            $span->setPath($this->getDetailPath());
            $span->addOption(Span::OPTION_DECORATION_NONE);
        }
        $this->pushField($span);
        $icon = new Icon(Icon::ICON_EXTERNAL_LINK);
        $icon->addOption('text-danger');
        $icon->setAccept(new CmsPageRedirectAccept());
        $this->pushField($icon);

        parent::initialize();
        $span = $this->addFieldOrderable('CmsPageType_Code', $this->translate('cmspagetype.code'));
        $span->setFormat(new CmsPageTypeFieldFormat($this->getTranslator()));
        if ($this->hasDetailPath()) {
            $span->setPath($this->getDetailPath());
            $span->addOption(Span::OPTION_DECORATION_NONE);
        }
        $span = $this->addFieldOrderable('CmsPageLayout_Code', $this->translate('cmspagelayout.code'));
        $span->setFormat(new CmsPageLayoutFieldFormat($this->getTranslator()));
        if ($this->hasDetailPath()) {
            $span->setPath($this->getDetailPath());
            $span->addOption(Span::OPTION_DECORATION_NONE);
        }
    }

    protected function getController(): string
    {
        return 'cmspage';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'CmsPage_ID'
        ];
    }
}
