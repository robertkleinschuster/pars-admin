<?php


namespace Pars\Admin\Article\Data;


use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\CrudController;

class ArticleDataController extends CrudController
{

    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('article.create', 'article.edit', 'article.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('article');
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