<?php

namespace Pars\Admin\Cms\PageParagraph;

use Pars\Admin\Article\ArticleController;
use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\ContentNavigation;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Cms\Paragraph\CmsParagraphController;
use Pars\Admin\Cms\Paragraph\CmsParagraphOverview;

/**
 * Class CmsPageParagraphController
 * @package Pars\Admin\Cms\PageParagraph
 * @method CmsPageParagraphModel getModel()
 */
class CmsPageParagraphController extends CmsParagraphController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmspageparagraph.create', 'cmspageparagraph.edit', 'cmspageparagraph.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmspageparagraph');
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
        $overview = new CmsPageParagraphOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $overview;
    }

    protected function createDetail(): BaseDetail
    {
        $detail = new CmsPageParagraphDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $detail;
    }

    protected function createEdit(): BaseEdit
    {
        $edit = new CmsPageParagraphEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $overview = new CmsParagraphOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $overview->setShowDeleteBulk(false);
        $overview->setShowCreate(false);
        $overview->setShowEdit(false);
        $overview->setShowDetail(false);
        $overview->setShowDelete(false);
        $overview->setBeanList($this->getModel()->getParagraphBeanList());
        $edit->getForm()->push($overview);
        return $edit;
    }

    protected function createDelete(): BaseDelete
    {
        $delete = new CmsPageParagraphDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $delete;
    }


}
