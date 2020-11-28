<?php


namespace Pars\Admin\Cms\Page;


use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Article\ArticleDetail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;

class CmsPageDetail extends ArticleDetail
{
    protected function initialize()
    {
        $span = new Badge('{CmsPageState_Code}');
        $span->setLabel($this->translate('cmspagestate.code'));
        $span->setFormat(new CmsPageStateFieldFormat($this->getTranslator()));
        $this->append($span);
        $span = new Span('{CmsPageType_Code}', $this->translate('cmspagetype.code'));
        $span->setFormat(new CmsPageTypeFieldFormat($this->getTranslator()));
        $this->append($span);
        parent::initialize();
    }


    protected function getIndexController(): string
    {
        return 'cmspage';
    }

    protected function getEditIdFields(): array
    {
        return [
            'CmsPage_ID'
        ];
    }

}
