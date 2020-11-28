<?php


namespace Pars\Admin\Cms\Paragraph;


use Pars\Admin\Article\ArticleDetail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;

class CmsParagraphDetail extends ArticleDetail
{
    protected function initialize()
    {
        $span = new Badge('{CmsParagraphState_Code}');
        $span->setLabel($this->translate('cmsparagraphstate.code'));
        $span->setFormat(new CmsParagraphStateFieldFormat($this->getTranslator()));
        $this->append($span);
        $span = new Span('{CmsParagrphType_Code}', $this->translate('cmsparagraphtype.code'));
        $span->setFormat(new CmsParagraphTypeFieldFormat($this->getTranslator()));
        $this->append($span);
        parent::initialize();
    }

    protected function getIndexController(): string
    {
        return 'cmsparagraph';
    }

    protected function getEditIdFields(): array
    {
        return [
            'CmsParagraph_ID'
        ];
    }

}
