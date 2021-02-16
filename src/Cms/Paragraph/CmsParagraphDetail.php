<?php


namespace Pars\Admin\Cms\Paragraph;


use Niceshops\Bean\Type\Base\BeanException;
use Pars\Admin\Article\ArticleDetail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;

class CmsParagraphDetail extends ArticleDetail
{
    /**
     *
     */
    protected function initSection()
    {
        $this->setSection($this->translate('section.paragraph'));
    }

    /**
     * @throws BeanException
     */
    protected function initFields()
    {
        $span = new Badge('{CmsParagraphState_Code}');
        $span->setLabel($this->translate('cmsparagraphstate.code'));
        $span->setFormat(new CmsParagraphStateFieldFormat($this->getTranslator()));
        $this->append($span, 2, 1);
        $span = new Span('{CmsParagrphType_Code}', $this->translate('cmsparagraphtype.code'));
        $span->setFormat(new CmsParagraphTypeFieldFormat($this->getTranslator()));
        $this->append($span, 2, 2);
        parent::initFields();
    }

    /**
     * @return string
     */
    protected function getIndexController(): string
    {
        return 'cmsparagraph';
    }

    /**
     * @return string[]
     */
    protected function getEditIdFields(): array
    {
        return [
            'CmsParagraph_ID'
        ];
    }

}
