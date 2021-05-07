<?php

namespace Pars\Admin\Cms\Post;

use Pars\Bean\Type\Base\BeanException;
use Pars\Admin\Article\ArticleDetail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;

class CmsPostDetail extends ArticleDetail
{
    /**
     * @throws BeanException
     */
    protected function initFields()
    {
        parent::initFields();
        $span = new Badge('{CmsPostState_Code}');
        $span->setLabel($this->translate('cmspoststate.code'));
        $span->setFormat(new CmsPostStateFieldFormat($this->getTranslator()));
        $span->setGroup($this->translate('article.group.visibility'));
        $this->pushField($span);
        $span = new Span('{CmsPostType_Code}', $this->translate('cmsposttype.code'));
        $span->setFormat(new CmsPostTypeFieldFormat($this->getTranslator()));
        $span->setGroup($this->translate('article.group.general'));
        $this->pushField($span);
        $span = $this->addSpan('CmsPost_PublishTimestamp', $this->translate('cmspost.publishtimestamp'));
        $span->setFormat(new CmsPostPublishTimestampFieldFormat());
        $span->setGroup($this->translate('article.group.visibility'));
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
