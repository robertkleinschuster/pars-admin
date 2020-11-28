<?php

namespace Pars\Admin\Cms\Page;

use Pars\Admin\Article\ArticleController;
use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\ContentNavigation;
use Pars\Helper\Parameter\IdParameter;



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
        $this->getView()->set('CmsPage_ID', (int) $this->getControllerRequest()->getId()->getAttribute('CmsPage_ID'));
        $this->addSubController('cmspageparagraph', 'index');
        return new CmsPageDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    protected function createEdit(): BaseEdit
    {
        $edit =  new CmsPageEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->setTypeOptions($this->getModel()->getCmsPageType_Options());
        $edit->setStateOptions($this->getModel()->getCmsPageState_Options());
        $edit->setFileOptions($this->getModel()->getFileOptions());
        return $edit;
    }

    protected function createDelete(): BaseDelete
    {
        return new CmsPageDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }
}
