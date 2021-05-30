<?php

namespace Pars\Admin\Cms\Block;

use Pars\Admin\Article\ArticleOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Field\Span;

class CmsBlockOverview extends ArticleOverview
{
    protected function initName()
    {
        $this->setName($this->translate('section.block'));
    }



    protected function initFields()
    {
        $this->addFieldState('CmsBlockState_Code');
        parent::initFields();

        $span = $this->addFieldOrderable('CmsBlockType_Code', $this->translate('cmsblocktype.code'));
        $span->setFormat(new CmsBlockTypeFieldFormat($this->getTranslator()));
        if ($this->hasDetailPath()) {
            $span->setPath($this->getDetailPath());
            $span->addOption(Span::OPTION_DECORATION_NONE);
        }
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
