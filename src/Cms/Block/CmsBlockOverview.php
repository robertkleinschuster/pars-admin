<?php

namespace Pars\Admin\Cms\Block;

use Pars\Admin\Article\ArticleOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;

class CmsBlockOverview extends ArticleOverview
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.block'));
        $span = new Badge('{CmsBlockState_Code}');
        $span->setFormat(new CmsBlockStateFieldFormat($this->getTranslator()));
        if ($this->hasDetailPath()) {
            $span->setPath($this->getDetailPath());
            $span->addOption(Span::OPTION_DECORATION_NONE);
        }
        $this->append($span);

        parent::initialize();

        $span = new Span('{CmsParagrphType_Code}', $this->translate('cmsblocktype.code'));
        $span->setFormat(new CmsBlockTypeFieldFormat($this->getTranslator()));
        if ($this->hasDetailPath()) {
            $span->setPath($this->getDetailPath());
            $span->addOption(Span::OPTION_DECORATION_NONE);
        }
        $this->append($span);
    }


    protected function getController(): string
    {
        return 'cmsblock';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'CmsBlock_ID'
        ];
    }
}
