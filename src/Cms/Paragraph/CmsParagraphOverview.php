<?php


namespace Pars\Admin\Cms\Paragraph;


use Pars\Admin\Article\ArticleOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;

class CmsParagraphOverview extends ArticleOverview
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.paragraph'));
        $span = new Badge('{CmsParagraphState_Code}');
        $span->setFormat(new CmsParagraphStateFieldFormat($this->getTranslator()));
        if ($this->hasDetailPath()) {
            $span->setPath($this->getDetailPath());
            $span->addOption(Span::OPTION_DECORATION_NONE);
        }
        $this->append($span);

        parent::initialize();

        $span = new Span('{CmsParagrphType_Code}', $this->translate('cmsparagraphtype.code'));
        $span->setFormat(new CmsParagraphTypeFieldFormat($this->getTranslator()));
        if ($this->hasDetailPath()) {
            $span->setPath($this->getDetailPath());
            $span->addOption(Span::OPTION_DECORATION_NONE);
        }
        $this->append($span);
    }


    protected function getController(): string
    {
        return 'cmsparagraph';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'CmsParagraph_ID'
        ];
    }

}
