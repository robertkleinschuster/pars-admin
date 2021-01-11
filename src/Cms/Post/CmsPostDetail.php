<?php


namespace Pars\Admin\Cms\Post;


use Pars\Admin\Article\ArticleDetail;
use Pars\Admin\Cms\Page\CmsPageStateFieldFormat;
use Pars\Admin\Cms\Page\CmsPageTypeFieldFormat;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;

class CmsPostDetail extends ArticleDetail
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.post'));
        $span = new Badge('{CmsPostState_Code}');
        $span->setLabel($this->translate('cmspoststate.code'));
        $span->setFormat(new CmsPostStateFieldFormat($this->getTranslator()));
        $this->append($span);
        $span = new Span('{CmsPostType_Code}', $this->translate('cmsposttype.code'));
        $span->setFormat(new CmsPostTypeFieldFormat($this->getTranslator()));
        $this->append($span);
        $this->addField('CmsPost_PublishTimestamp', $this->translate('cmspost.publishtimestamp'));
        parent::initialize();
    }


    protected function getIndexController(): string
    {
        return 'cmspage';
    }

    protected function getEditIdFields(): array
    {
        return ['CmsPost_ID'];
    }

    protected function getEditController(): string
    {
        return 'cmspost';
    }

    protected function getEditAction(): string
    {
        return 'edit';
    }

    protected function getIndexAction(): string
    {
        return 'detail';
    }

    protected function getIndexIdFields(): array
    {
        return ['CmsPage_ID'];
    }


}
