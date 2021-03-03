<?php

namespace Pars\Admin\Cms\Post;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Article\ArticleModel;
use Pars\Model\Cms\Page\CmsPageBeanFinder;
use Pars\Model\Cms\Post\CmsPostBeanFinder;
use Pars\Model\Cms\Post\CmsPostBeanProcessor;
use Pars\Model\Cms\Post\State\CmsPostStateBeanFinder;
use Pars\Model\Cms\Post\Type\CmsPostTypeBeanFinder;

/**
 * Class CmsPostModel
 * @package Pars\Admin\Cms\Post
 */
class CmsPostModel extends ArticleModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsPostBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsPostBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }



    public function getCmsPostType_Options(): array
    {
        $options = [];
        $finder = new CmsPostTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsPostType_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPostType_Code')] = $this->translate('cmsposttype.code.' . $bean->get('CmsPostType_Code'));
        }
        return $options;
    }

    public function getCmsPostState_Options(): array
    {
        $options = [];
        $finder = new CmsPostStateBeanFinder($this->getDbAdpater());
        $finder->setCmsPostState_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPostState_Code')] = $this->translate('cmspoststate.code.' . $bean->get('CmsPostState_Code'));
        }
        return $options;
    }

    public function getEmptyBean(array $data = []): BeanInterface
    {
        $bean = parent::getEmptyBean($data);
        $bean->set('CmsPost_PublishTimestamp', new \DateTime());
        if (isset($data['CmsPage_ID'])) {
            $finder = new CmsPageBeanFinder($this->getDbAdpater());
            $finder->setLocale_Code($this->getTranslator()->getLocale());
            $finder->setCmsPage_ID($data['CmsPage_ID']);
            $page = $finder->getBean();
            $count = $page->get('CmsBlock_BeanList')->count() + 1;
            $code =  $page->get('Article_Code') . '-' . $count;
            $i = 1;
            while ((new CmsPageBeanFinder($this->getDbAdpater()))->setArticle_Code($code)->count()) {
                $code .= '-' . $i;
            }
            $bean->set('Article_Code', $code);
            $code =  $page->get('ArticleTranslation_Code') . '-' . $count;
            $i = 1;
            while ((new CmsPageBeanFinder($this->getDbAdpater()))->setArticleTranslation_Code($code)->count()) {
                $code .= '-' . $i;
            }
            $bean->set('ArticleTranslation_Code', $code);
            $bean->set('ArticleTranslation_Name', $page->get('ArticleTranslation_Name'));
        }
        return $bean;
    }
}
