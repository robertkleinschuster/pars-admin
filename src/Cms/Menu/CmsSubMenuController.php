<?php

namespace Pars\Admin\Cms\Menu;

use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\ContentNavigation;
use Pars\Admin\Base\CrudController;


/**
 * Class CmsMenuController
 * @package Pars\Admin\Cms\Menu
 * @method CmsMenuModel getModel()
 */
class CmsSubMenuController extends CmsMenuController
{
    protected function initView()
    {
        parent::initView();

    }


    protected function handleParameter()
    {
        if ($this->getControllerRequest()->getAction() == 'index') {
            $id = $this->getControllerRequest()->getId();
            $id->addId('CmsMenu_ID_Parent', $id->getAttribute('CmsMenu_ID'));
            $id->unsetAttribute('CmsMenu_ID');
            $this->getControllerRequest()->setAttribute($id::name(), $id->toString());
        }
        parent::handleParameter();
    }


    protected function createOverview(): BaseOverview
    {
        $overview = new CmsSubMenuOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        if (count($this->getModel()->getCmsPage_Options()) == 0) {
            $overview->setShowCreate(false);
        }
        return $overview;
    }

    protected function createDetail(): BaseDetail
    {
        return new CmsSubMenuDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    protected function createEdit(): BaseEdit
    {
        $edit = new CmsSubMenuEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->setStateOptions($this->getModel()->getCmsMenuState_Options());
        $edit->setPageOptions($this->getModel()->getCmsPage_Options());
        return $edit;
    }

    protected function createDelete(): BaseDelete
    {
        return new CmsMenuDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }
}
