<?php

namespace Pars\Admin\Cms\Page;

use Pars\Admin\Article\ArticleModel;
use Pars\Admin\Base\CrudModel;
use Pars\Model\Cms\Page\State\CmsPageStateBeanFinder;
use Pars\Model\Cms\Page\Type\CmsPageTypeBeanFinder;
use Pars\Model\Cms\Page\CmsPageBeanFinder;
use Pars\Model\Cms\Page\CmsPageBeanProcessor;
use Pars\Model\Cms\PageParagraph\CmsPageParagraphBeanFinder;

/**
 * Class CmsPageModel
 * @package Pars\Admin\Cms\Page
 */
class CmsPageModel extends ArticleModel
{

    /**
     * @inheritDoc
     */
    public function initialize()
    {
        $this->setBeanFinder(new CmsPageBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsPageBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }

    public function getCmsPageType_Options(): array
    {
        $options = [];
        $finder = new CmsPageTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsPageType_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPageType_Code')] = $this->translate("cmspagetype.code." . $bean->get('CmsPageType_Code'));
        }
        return $options;
    }

    public function getCmsPageState_Options(): array
    {
        $options = [];
        $finder = new CmsPageStateBeanFinder($this->getDbAdpater());
        $finder->setCmsPageState_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPageState_Code')] = $this->translate("cmspagestate.code." . $bean->get('CmsPageState_Code'));
        }
        return $options;
    }
}
