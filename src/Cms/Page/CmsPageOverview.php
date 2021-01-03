<?php


namespace Pars\Admin\Cms\Page;


use Pars\Admin\Article\ArticleOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Field\Span;

class CmsPageOverview extends ArticleOverview
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.page'));
        $span = new Badge('{CmsPageState_Code}');
        $span->setFormat(new CmsPageStateFieldFormat($this->getTranslator()));
        if ($this->hasDetailPath()) {
            $span->setPath($this->getDetailPath());
            $span->addOption(Span::OPTION_DECORATION_NONE);
        }
        $this->append($span);
        $icon = new Icon(Icon::ICON_EXTERNAL_LINK);
        $icon->addOption('text-danger');
        $icon->setAccept(new CmsPageRedirectAccept());
        $this->append($icon);
        $span = new Span('{CmsPageType_Code}', $this->translate('cmspagetype.code'));
        $span->setFormat(new CmsPageTypeFieldFormat($this->getTranslator()));
        if ($this->hasDetailPath()) {
            $span->setPath($this->getDetailPath());
            $span->addOption(Span::OPTION_DECORATION_NONE);
        }
        $this->append($span);
        $span = new Span('{CmsPageLayout_Code}', $this->translate('cmspagelayout.code'));
        $span->setFormat(new CmsPageLayoutFieldFormat($this->getTranslator()));
        if ($this->hasDetailPath()) {
            $span->setPath($this->getDetailPath());
            $span->addOption(Span::OPTION_DECORATION_NONE);
        }
        $this->append($span);
        parent::initialize();
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
