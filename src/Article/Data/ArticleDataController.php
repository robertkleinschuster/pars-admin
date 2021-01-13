<?php


namespace Pars\Admin\Article\Data;


use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\ContentNavigation;
use Pars\Admin\Base\CrudController;

class ArticleDataController extends CrudController
{

    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('article.create', 'article.edit', 'article.delete');
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

    public function isAuthorized(): bool
    {
        return $this->checkPermission('article');
    }

    public function indexAction()
    {
        if ($this->hasParent() && $this->getParent()->hasView()) {
            $parentBean = $this->getParent()->getModel()->getBean();
            $this->getModel()->getBeanFinder()->filter(['Article_ID' => $parentBean->get('Article_ID')]);
            $overview = parent::indexAction();
            $overview->set('parent', $parentBean);
        } else {
            $overview = parent::indexAction();
        }
        return $overview;
    }


    protected function createOverview(): BaseOverview
    {
        return new ArticleDataOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    protected function createDetail(): BaseDetail
    {
        return new ArticleDataDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    protected function createEdit(): BaseEdit
    {
        return new ArticleDataEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    protected function createDelete(): BaseDelete
    {
        return new ArticleDataDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

}
