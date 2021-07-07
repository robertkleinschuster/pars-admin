<?php

namespace Pars\Admin\Cms\Post;

use Pars\Admin\Article\ArticleOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;

class CmsPostOverview extends ArticleOverview
{
    protected function initName()
    {
        $this->setName($this->translate('section.post'));
    }


    protected function initialize()
    {
        $span = new Badge('{CmsPostState_Code}');
        $span->setFormat(new CmsPostStateFieldFormat($this->getTranslator()));
        if ($this->hasDetailPath()) {
            $span->setPath($this->getDetailPath());
            $span->addOption(Span::OPTION_DECORATION_NONE);
        }
        $this->pushField($span);
        $span = new Span('{CmsPost_PublishTimestamp}', $this->translate('cmspost.publishtimestamp'));
        $span->setFormat(new CmsPostPublishTimestampFieldFormat());
        $this->pushField($span);
        parent::initialize();
        $span = new Span('{CmsPostType_Code}', $this->translate('cmsposttype.code'));
        $span->setFormat(new CmsPostTypeFieldFormat($this->getTranslator()));
        if ($this->hasDetailPath()) {
            $span->setPath($this->getDetailPath());
            $span->addOption(Span::OPTION_DECORATION_NONE);
        }
        $this->pushField($span);
    }

    protected function initBase()
    {
        parent::initBase();
        $this->setShowOrder(false);
    }


    protected function getController(): string
    {
        return 'cmspost';
    }

    protected function getDetailIdFields(): array
    {
        return ['CmsPost_ID', 'CmsPage_ID',
        ];
    }

    protected function getRedirectController(): string
    {
        return 'cmspage';
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

    protected function getRedirectIdFields(): array
    {
        return ['CmsPage_ID'];
    }

    protected function getCreateIdFields(): array
    {
        if ($this->getControllerRequest()->hasId()) {
            return $this->getControllerRequest()->getId()->getAttributes();
        }
        return ['CmsPage_ID'];
    }
}
