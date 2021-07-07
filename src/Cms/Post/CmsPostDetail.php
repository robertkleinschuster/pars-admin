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
        $this->addFieldType();
        $this->addFieldState();
        $this->addFieldPublishTimestamp();
        $this->addFieldCodeUrl();
        $this->addFieldCodeInternal();
        $this->addFieldName();
        $this->addFieldTitle();
        $this->addFieldHeading();
        $this->addFieldSubHeading();
        $this->addFieldKeywords();
        $this->addFieldTeaser();
        $this->addFieldText();
        $this->addFieldFooter();
    }

    protected function addFieldState()
    {
        $span = new Badge('{CmsPostState_Code}');
        $span->setLabel($this->translate('cmspoststate.code'));
        $span->setFormat(new CmsPostStateFieldFormat($this->getTranslator()));
        $span->setGroup($this->getGroupGeneral());
        $this->pushField($span);
    }

    protected function addFieldType()
    {
        $span = new Span('{CmsPostType_Code}', $this->translate('cmsposttype.code'));
        $span->setFormat(new CmsPostTypeFieldFormat($this->getTranslator()));
        $span->setGroup($this->getGroupGeneral());
        $this->pushField($span);
    }

    protected function addFieldPublishTimestamp()
    {
        $span = $this->addSpan('CmsPost_PublishTimestamp', $this->translate('cmspost.publishtimestamp'));
        $span->setFormat(new CmsPostPublishTimestampFieldFormat());
        $span->setGroup($this->getGroupGeneral());
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
