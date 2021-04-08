<?php

namespace Pars\Admin\Locale;

use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\SystemNavigation;

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

    public function editAction()
    {
        $edit = parent::editAction();
        $edit->setDomain_List($this->getModel()->getDomain_List());
        return $edit;
    }


}
