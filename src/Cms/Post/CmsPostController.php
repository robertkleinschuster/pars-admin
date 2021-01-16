<?php

namespace Pars\Admin\Cms\Post;

use Pars\Admin\Article\ArticleController;
use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\ContentNavigation;

/**
 * Class CmsPostController
 * @package Pars\Admin\Cms\Post
 * @method CmsPostModel getModel()
 */
class CmsPostController extends ArticleController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmspost.create', 'cmspost.edit', 'cmspost.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmspost');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('content');
        $subNavigation = new ContentNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('cmspage');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
        if ($this->getControllerRequest()->hasId() && $this->getControllerRequest()->getId()->hasAttribute('CmsPage_ID')) {
            $this->getView()->set('CmsPage_ID', (int) $this->getControllerRequest()->getId()->getAttribute('CmsPage_ID'));
        }
    }

    protected function createOverview(): BaseOverview
    {
        return new CmsPostOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    protected function createDetail(): BaseDetail
    {
        $detail = new CmsPostDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $detail;
    }

    protected function createEdit(): BaseEdit
    {
        $edit = new CmsPostEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->setStateOptions($this->getModel()->getCmsPostState_Options());
        $edit->setTypeOptions($this->getModel()->getCmsPostType_Options());
        $edit->setFileOptions($this->getModel()->getFileOptions());
        return $edit;
    }

    protected function createDelete(): BaseDelete
    {
        return new CmsPostDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

}
