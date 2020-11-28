<?php

namespace Pars\Admin\Cms\Menu;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\ContentNavigation;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\SystemNavigation;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\MoveParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Path\PathHelper;


/**
 * Class CmsMenuController
 * @package Pars\Admin\Cms\Menu
 * @method CmsMenuModel getModel()
 */
class CmsMenuController extends CrudController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmsmenu.create', 'cmsmenu.edit', 'cmsmenu.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmsmenu');
    }
    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('content');
        $subNavigation = new ContentNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('cmsmenu');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

    protected function createOverview(): BaseOverview
    {
        $overview = new CmsMenuOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        if (count($this->getModel()->getCmsPage_Options()) == 0) {
            $overview->setShowCreate(false);
        }
        return $overview;
    }

    protected function createDetail(): BaseDetail
    {
        return new CmsMenuDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    protected function createEdit(): BaseEdit
    {
        $edit = new CmsMenuEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->setStateOptions($this->getModel()->getCmsMenuState_Options());
        $edit->setTypeOptions($this->getModel()->getCmsMenuType_Options());
        $edit->setPageOptions($this->getModel()->getCmsPage_Options());
        return $edit;
    }

    protected function createDelete(): BaseDelete
    {
        return new CmsMenuDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }
}
