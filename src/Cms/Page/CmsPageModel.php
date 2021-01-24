<?php

namespace Pars\Admin\Cms\Page;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Article\ArticleModel;
use Pars\Model\Article\DataBean;
use Pars\Model\Cms\Page\CmsPageBeanFinder;
use Pars\Model\Cms\Page\CmsPageBeanProcessor;
use Pars\Model\Cms\Page\Layout\CmsPageLayoutBeanFinder;
use Pars\Model\Cms\Page\State\CmsPageStateBeanFinder;
use Pars\Model\Cms\Page\Type\CmsPageTypeBeanFinder;

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

    public function getCmsPageLayout_Options(): array
    {
        $options = [];
        $finder = new CmsPageLayoutBeanFinder($this->getDbAdpater());
        $finder->setCmsPageLayout_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPageLayout_Code')] = $this->translate("cmspagelayout.code." . $bean->get('CmsPageLayout_Code'));
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

    public function getCmsPageRedirect_Options(): array
    {
        $options = [];
        $finder = new CmsPageBeanFinder($this->getDbAdpater());
        $finder->setLocale_Code($this->getTranslator()->getLocale());
        $finder->setCmsPageState_Code('active');
        $options[null] = $this->translate('noselection');
        foreach ($finder->getBeanListDecorator() as $bean) {
            if ($this->getBeanFinder()->count() != 1 || $bean->get('CmsPage_ID') != $this->getBean()->get('CmsPage_ID')) {
                $options[$bean->get('CmsPage_ID')] = $bean->get('ArticleTranslation_Name');
            }
        }
        return $options;
    }

    public function getEmptyBean(array $data = []): BeanInterface
    {
        $bean = parent::getEmptyBean($data);
        $bean->set('CmsPageType_Code', 'home');
        $bean->set('CmsPageLayout_Code', 'default');
        $bean->set('CmsPageState_Code', 'active');
        $bean->set('Article_Data', new DataBean());
        return $bean;
    }
}
