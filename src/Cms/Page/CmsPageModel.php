<?php

namespace Pars\Admin\Cms\Page;

use Laminas\Diactoros\UploadedFile;
use Pars\Bean\Type\Base\BeanInterface;
use Pars\Admin\Article\ArticleModel;
use Pars\Core\Cache\ParsCache;
use Pars\Helper\Parameter\IdListParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Model\Article\DataBean;
use Pars\Model\Cms\Page\CmsPageBeanFinder;
use Pars\Model\Cms\Page\CmsPageBeanProcessor;
use Pars\Model\Cms\Page\Layout\CmsPageLayoutBeanFinder;
use Pars\Model\Cms\Page\State\CmsPageStateBeanFinder;
use Pars\Model\Cms\Page\Type\CmsPageTypeBeanFinder;
use Pars\Model\Cms\PageBlock\CmsPageBlockBeanProcessor;
use Pars\Model\Cms\Block\CmsBlockBeanFinder;
use Pars\Model\Cms\Block\CmsBlockBeanProcessor;
use Pars\Model\Cms\Post\CmsPostBeanFinder;
use Pars\Model\Cms\Post\CmsPostBeanProcessor;

/**
 * Class CmsPageModel
 * @package Pars\Admin\Cms\Page
 * @method CmsPageBeanFinder getBeanFinder()
 * @method CmsPageBeanProcessor getBeanProcessor()
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

    public function getCmsPageType_Options(bool $emptyElement = false): array
    {
        $options = [];
        if ($emptyElement) {
            $options[''] = $this->translate('noselection');
        }
        $finder = new CmsPageTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsPageType_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPageType_Code')] = $this->translate("cmspagetype.code." . $bean->get('CmsPageType_Code'));
        }
        return $options;
    }

    public function getCmsPageLayout_Options(bool $emptyElement = false): array
    {
        $options = [];
        if ($emptyElement) {
            $options[''] = $this->translate('noselection');
        }
        $finder = new CmsPageLayoutBeanFinder($this->getDbAdpater());
        $finder->setCmsPageLayout_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPageLayout_Code')] = $this->translate("cmspagelayout.code." . $bean->get('CmsPageLayout_Code'));
        }
        return $options;
    }

    public function getCmsPageState_Options(bool $emptyElement = false): array
    {
        $options = [];
        if ($emptyElement) {
            $options[''] = $this->translate('noselection');
        }
        $finder = new CmsPageStateBeanFinder($this->getDbAdpater());
        $finder->setCmsPageState_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPageState_Code')] = $this->translate("cmspagestate.code." . $bean->get('CmsPageState_Code'));
        }
        return $options;
    }

    public function getCmsPageRedirect_Options(): array
    {
        static $options = [];
        if (count($options) == 0) {
            $cache = $this->getCache(__METHOD__);
            if (!$cache->has('options')) {
                $finder = new CmsPageBeanFinder($this->getDbAdpater(), false);
                $finder->setLocale_Code($this->getTranslator()->getLocale());
                $finder->setCmsPageState_Code('active');
                $options[null] = $this->translate('noselection');
                foreach ($finder->getBeanListDecorator() as $bean) {
                    if ($this->getBeanFinder()->count() != 1 || $bean->get('CmsPage_ID') != $this->getBean()->get('CmsPage_ID')) {
                        $options[$bean->get('CmsPage_ID')] = $bean->get('ArticleTranslation_Name');
                    }
                }
                $cache->set('options', $options, 300);
            } else {
                $options = $cache->get('options');
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
        return $bean;
    }

    protected function delete(IdParameter $idParameter): void
    {
        $finder = new CmsPostBeanFinder($this->getDbAdpater());
        $finder->setCmsPage_ID($idParameter->getAttribute('CmsPage_ID'));
        $processor = new CmsPostBeanProcessor($this->adapter);
        $processor->setBeanList($finder->getBeanList());
        $processor->delete();
        parent::delete($idParameter);
    }

    protected function delete_bulk(IdListParameter $idListParameter, array $attributes): void
    {
        $finder = new CmsPostBeanFinder($this->getDbAdpater());
        $finder->filter($idListParameter->getAttribute_List());
        $processor = new CmsPostBeanProcessor($this->adapter);
        $processor->setBeanList($finder->getBeanList());
        $processor->delete();
        parent::delete_bulk($idListParameter, $attributes);
    }


    public function handleSubmit(SubmitParameter $submitParameter, IdParameter $idParameter, IdListParameter $idListParameter, array $attribute_List)
    {
        parent::handleSubmit($submitParameter, $idParameter, $idListParameter, $attribute_List);
        if ($submitParameter->getMode() == 'import') {
            if ($this->hasOption(self::OPTION_CREATE_ALLOWED)) {
                $this->import($attribute_List);
            } else {
                $this->handlePermissionDenied();
            }
        }
    }


    public function export(): array
    {
        $data = [];
        foreach ($this->getLocale_List() as $locale) {
            $finder = new CmsPageBeanFinder($this->getDbAdpater());
            $finder->setLocale_Code($locale->get('Locale_Code'));
            $finder->setCmsPage_ID($this->getBean()->get('CmsPage_ID'));
            foreach ($finder->getBeanListDecorator() as $bean) {
                $data[$locale->get('Locale_Code')] = $bean;
            }
        }
        return $data;
    }

    public function import(array $data)
    {
        $file = $data['CmsPage_Import'];
        if ($file instanceof UploadedFile) {
            $pageList = (new CmsPageBeanFinder($this->getDbAdpater()))->getBeanFactory()->getEmptyBeanList();
            $data = json_decode($file->getStream()->getContents(), true);
            foreach ($data as $locale => $datum) {
                $this->getDbAdpater()->getDriver()->getConnection()->beginTransaction();
                $page = $this->getEmptyBean($datum);
                $page->fromArray($datum);
                $page->unset('CmsPage_ID');
                $page->unset('Article_ID');
                $page->unset('File_ID');
                $page->unset('Person_ID_Create');
                $page->unset('Person_ID_Edit');
                $pageFinder = new CmsPageBeanFinder($this->getDbAdpater());
                $pageFinder->setLocale_Code($locale);
                $pageFinder->setArticle_Code($page->get('Article_Code'));
                if ($pageFinder->count()) {
                    $bean = $pageFinder->getBean();
                    $page->set('CmsPage_ID', $bean->get('CmsPage_ID'));
                    $page->set('Article_ID', $bean->get('Article_ID'));
                }

                $pageList->push($page);

                $processor = new CmsPageBeanProcessor($this->getDbAdpater());
                $processor->setTranslator($this->getTranslator());
                $processor->setBeanList($pageList);
                $processor->save();
                $this->getDbAdpater()->getDriver()->getConnection()->commit();

                foreach ($pageList as $page) {
                    $postList = $page->get('CmsPost_BeanList');
                    foreach ($postList as $post) {
                        $post->unset('CmsPost_ID');
                        $post->unset('Article_ID');
                        $post->unset('File_ID');
                        $post->unset('Person_ID_Create');
                        $post->unset('Person_ID_Edit');
                        $post->set('CmsPage_ID', $page->get('CmsPage_ID'));
                        $finder = new CmsPostBeanFinder($this->getDbAdpater());
                        $finder->setLocale_Code($locale);
                        $finder->setArticle_Code($post->get('Article_Code'));
                        if ($finder->count()) {
                            $bean = $finder->getBean();
                            $post->set('CmsPost_ID', $bean->get('CmsPost_ID'));
                            $post->set('Article_ID', $bean->get('Article_ID'));
                        }
                    }
                    $processor = new CmsPostBeanProcessor($this->getDbAdpater());
                    $processor->setTranslator($this->getTranslator());
                    $processor->setBeanList($postList);
                    $processor->save();

                    $blockList = $page->get('CmsBlock_BeanList');
                    foreach ($blockList as $block) {
                        $block->unset('CmsPage_ID');
                        $block->unset('CmsBlock_ID');
                        $block->unset('Article_ID');
                        $block->unset('File_ID');
                        $block->unset('Person_ID_Create');
                        $block->unset('Person_ID_Edit');
                        $finder = new CmsBlockBeanFinder($this->getDbAdpater());
                        $finder->setLocale_Code($locale);
                        $finder->setArticle_Code($block->get('Article_Code'));
                        if ($finder->count()) {
                            $bean = $finder->getBean();
                            $block->set('CmsBlock_ID', $bean->get('CmsBlock_ID'));
                            $block->set('Article_ID', $bean->get('Article_ID'));
                        }
                    }

                    $processor = new CmsBlockBeanProcessor($this->getDbAdpater());
                    $processor->setTranslator($this->getTranslator());
                    $processor->setBeanList($blockList);
                    $processor->save();

                    foreach ($blockList as $block) {
                        $block->set('CmsPage_ID', $page->get('CmsPage_ID'));
                    }

                    $processor = new CmsPageBlockBeanProcessor($this->getDbAdpater());
                    $processor->setBeanList($blockList);
                    $processor->save();
                }
            }
        }
    }
}
