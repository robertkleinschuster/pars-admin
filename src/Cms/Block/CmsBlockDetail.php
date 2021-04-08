<?php

namespace Pars\Admin\Cms\Block;

use Pars\Bean\Type\Base\BeanException;
use Pars\Admin\Article\ArticleDetail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;

class CmsBlockDetail extends ArticleDetail
{

    /**
     * @throws BeanException
     */
    protected function initFields()
    {
        $span = new Badge('{CmsBlockState_Code}');
        $span->setLabel($this->translate('cmsblockstate.code'));
        $span->setFormat(new CmsBlockStateFieldFormat($this->getTranslator()));
        $this->append($span, 2, 1);
        $span = new Span('{CmsBlockType_Code}', $this->translate('cmsblocktype.code'));
        $span->setFormat(new CmsBlockTypeFieldFormat($this->getTranslator()));
        $this->append($span, 2, 2);
        parent::initFields();
    }

    /**
     * @return string
     */
    protected function getIndexController(): string
    {
        return 'cmsblock';
    }

    /**
     * @return string[]
     */
    protected function getEditIdFields(): array
    {
        return [
            'CmsBlock_ID'
        ];
    }
}
