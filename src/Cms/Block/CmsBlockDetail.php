<?php

namespace Pars\Admin\Cms\Block;

use Pars\Bean\Type\Base\BeanException;
use Pars\Admin\Article\ArticleDetail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;

class CmsBlockDetail extends ArticleDetail
{

    protected function initFields()
    {
        parent::initFields();
        $this->addFieldType();
        $this->addFieldState();
        $this->addFieldCodeInternal();
        $this->addFieldCodeUrl();
        if ($this->hasBean()) {
            $this->initFieldsByType($this->getBean()->get('CmsBlockType_Code'));
        }
    }

    protected function initFieldsByType(string $type)
    {
        switch ($type) {
            case 'card':
                $this->initFieldsCard();
                break;
            case 'banner':
                $this->initFieldsBanner();
                break;
            case 'contact':
                $this->initFieldsContact();
                break;
            case 'link':
                $this->initFieldsLink();
                break;
            case 'picture':
                $this->initFieldsPicture();
                break;
            case 'poll':
                $this->initFieldsPoll();
                break;
            case 'text':
                $this->initFieldsText();
                break;
            case 'tiles':
                $this->initFieldsTiles();
                break;
            case 'video':
                $this->initFieldsVideo();
                break;
            case 'gallery':
                $this->initFieldsGallery();
                break;
            case 'default':
                $this->initFieldsDefault();
                break;
        }
    }

    protected function initFieldsCard()
    {
        $this->addFieldHeading();
        $this->addFieldText();
        $this->addFieldPath();
    }

    protected function initFieldsGallery()
    {
        $this->addFieldHeading();
    }

    protected function initFieldsBanner()
    {
        $this->addFieldPath($this->translate('cmsblock.banner.path'));
        $this->addFieldHeading($this->translate('cmsblock.banner.heading'));
        $this->addFieldSubHeading($this->translate('cmsblock.banner.subheading'));
        $this->addFieldText($this->translate('cmsblock.banner.text'));
    }
    protected function initFieldsLink()
    {
        $this->addFieldPath($this->translate('cmsblock.link.path'));
        $this->addFieldHeading($this->translate('cmsblock.link.heading'));
    }

    protected function initFieldsPicture()
    {

    }

    protected function initFieldsPoll()
    {
        $this->addFieldHeading($this->translate('cmsblock.poll.heading'));
        $this->addFieldText($this->translate('cmsblock.poll.text'));
    }

    protected function initFieldsTiles()
    {
        $this->addFieldText();
        $this->addFieldHeading($this->translate('cmsblock.poll.heading'));
        $this->addFieldFooter();
    }

    protected function initFieldsVideo()
    {
        $this->addFieldPath($this->translate('cmsblock.video.path'));
    }

    protected function initFieldsDefault()
    {
        $this->addFieldText($this->translate('cmsblock.default.text'));
    }

    protected function initFieldsText()
    {
        $this->addFieldText($this->translate('cmsblock.text.text'));
    }

    protected function initFieldsContact()
    {
        $this->addFieldContactEmail();
    }





    protected function addFieldState()
    {
        $span = new Badge('{CmsBlockState_Code}');
        $span->setLabel($this->translate('cmsblockstate.code'));
        $span->setFormat(new CmsBlockStateFieldFormat($this->getTranslator()));
        $span->setGroup($this->getGroupGeneral());
        $this->pushField($span);
    }

    protected function addFieldType()
    {
        $span = new Span('{CmsBlockType_Code}', $this->translate('cmsblocktype.code'));
        $span->setFormat(new CmsBlockTypeFieldFormat($this->getTranslator()));
        $span->setGroup($this->getGroupGeneral());
        $this->pushField($span);
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
