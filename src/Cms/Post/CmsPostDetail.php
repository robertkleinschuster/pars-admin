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
        $span = new Badge('{CmsPostState_Code}');
        $span->setLabel($this->translate('cmspoststate.code'));
        $span->setFormat(new CmsPostStateFieldFormat($this->getTranslator()));
        $this->append($span, 2, 1);
        $span = new Span('{CmsPostType_Code}', $this->translate('cmsposttype.code'));
        $span->setFormat(new CmsPostTypeFieldFormat($this->getTranslator()));
        $this->append($span, 2, 2);
        $this->addField(
            'CmsPost_PublishTimestamp',
            $this->translate('cmspost.publishtimestamp'),
            3,
            1
        )->setFormat(new CmsPostPublishTimestampFieldFormat());
        parent::initFields();
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
