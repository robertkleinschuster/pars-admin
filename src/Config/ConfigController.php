<?php


namespace Pars\Admin\Config;


use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\SystemNavigation;

class ConfigController extends CrudController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('config.create', 'config.edit', 'config.delete');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('system');
        $subNavigation = new SystemNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('config');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->checkPermission('user');
    }

    protected function createOverview(): BaseOverview
    {
        return new ConfigOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    protected function createDetail(): BaseDetail
    {
        return new ConfigDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    protected function createEdit(): BaseEdit
    {
        return new ConfigEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    protected function createDelete(): BaseDelete
    {
        return new ConfigDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

}
