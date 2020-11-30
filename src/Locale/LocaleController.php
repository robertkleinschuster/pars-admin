<?php

namespace Pars\Admin\Locale;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\SystemNavigation;
use Pars\Mvc\Helper\PathHelper;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\MoveParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Overview\Overview;

class LocaleController extends CrudController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('locale.create', 'locale.edit', 'locale.delete');
    }


    public function isAuthorized(): bool
    {
        return $this->checkPermission('locale');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('system');
        $subNavigation = new SystemNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('locale');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }


    protected function createOverview(): BaseOverview
    {
        $overview = new LocaleOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $overview;
    }

    protected function createDetail(): BaseDetail
    {
        $detail = new LocaleDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $detail;
    }

    protected function createEdit(): BaseEdit
    {
        $edit = new LocaleEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $edit;
    }

    protected function createDelete(): BaseDelete
    {
        $delete = new LocaleDelete($this->getPathHelper(), $this->getTranslator(),$this->getUserBean());
        return $delete;
    }
}
