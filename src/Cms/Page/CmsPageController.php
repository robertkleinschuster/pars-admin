<?php

namespace Pars\Admin\Cms\Page;

use Niceshops\Bean\Type\Base\BeanListInterface;
use Pars\Admin\Article\ArticleController;
use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\ContentNavigation;
use Pars\Model\Article\ArticleBean;
use Pars\Model\Cms\Paragraph\CmsParagraphBeanFinder;
use Pars\Model\Cms\Paragraph\CmsParagraphBeanProcessor;


/**
 * Class CmsPageController
 * @package Pars\Admin\Cms\Page
 * @method CmsPageModel getModel()
 */
class CmsPageController extends ArticleController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmspage.create', 'cmspage.edit', 'cmspage.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmspage');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('content');
        $subNavigation = new ContentNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('cmspage');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

    protected function createOverview(): BaseOverview
    {
        return new CmsPageOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    protected function createDetail(): BaseDetail
    {
        $this->getView()->set('CmsPage_ID', (int)$this->getControllerRequest()->getId()->getAttribute('CmsPage_ID'));
        $this->addSubController('cmspageparagraph', 'index');
        return new CmsPageDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    protected function createEdit(): BaseEdit
    {
        $edit = new CmsPageEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->setTypeOptions($this->getModel()->getCmsPageType_Options());
        $edit->setStateOptions($this->getModel()->getCmsPageState_Options());
        $edit->setFileOptions($this->getModel()->getFileOptions());
        return $edit;
    }

    protected function createDelete(): BaseDelete
    {
        return new CmsPageDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    public function detailAction()
    {
        $this->handlePoll();
        parent::detailAction();
    }

    protected function handlePoll()
    {
        $bean = $this->getModel()->getBean();
        if ($bean->get('CmsPageType_Code') == 'poll') {
            $paragraphList = $bean->get('CmsParagraph_BeanList');
            if ($paragraphList instanceof BeanListInterface) {
                $detail = new CmsPagePollDetail();
                $detail->setResetPath($this->getPathHelper(true) . '&reset_poll=1');
                $detail->setShowPath($this->getPathHelper(true) . '&show_poll=1');
                $detail->setBean($bean);
                $this->getView()->append($detail);

                if ($this->getControllerRequest()->hasAttribute('reset_poll')) {
                    $paragraphFinder = new CmsParagraphBeanFinder($this->getModel()->getDbAdpater());
                    $paragraphFinder->initByValueList('Article_Code', $paragraphList->column('Article_Code'));
                    $beanList = $paragraphFinder->getBeanList(true);
                    foreach ($beanList as $item) {
                        $item->getArticle_Data()->set('poll', 0);
                        $item->getArticle_Data()->set('poll_value', 0);
                    }
                    $paragraphProcessor = new CmsParagraphBeanProcessor($this->getModel()->getDbAdpater());
                    $paragraphProcessor->setBeanList($beanList);
                    $paragraphProcessor->save();
                    $this->getControllerResponse()->setRedirect($this->getPathHelper(true)->getPath());
                }
                $resultMap = [];
                foreach ($paragraphList as $paragraph) {
                    if ($paragraph instanceof ArticleBean) {
                        if ($paragraph->getArticle_Data()->exists('poll')) {
                            $resultMap[$paragraph->ArticleTranslation_Title] = $paragraph->getArticle_Data()->get('poll');
                        }
                    }
                }
                if (count($resultMap)) {
                    $max = max($resultMap);
                    if ($this->getControllerRequest()->hasAttribute('show_poll')) {
                        $paragraphFinder = new CmsParagraphBeanFinder($this->getModel()->getDbAdpater());
                        $paragraphFinder->initByValueList('Article_Code', $paragraphList->column('Article_Code'));
                        $beanList = $paragraphFinder->getBeanList(true);
                        foreach ($beanList as $item) {
                            $value = $item->getArticle_Data()->get('poll');
                            if ($max > 0 && $value > 0) {
                                $item->getArticle_Data()->set('poll_value', $value / $max * 100);
                            }
                        }
                        $paragraphProcessor = new CmsParagraphBeanProcessor($this->getModel()->getDbAdpater());
                        $paragraphProcessor->setBeanList($beanList);
                        $paragraphProcessor->save();
                        $this->getControllerResponse()->setRedirect($this->getPathHelper(true)->getPath());
                    }
                }

            }
        }
    }

}
