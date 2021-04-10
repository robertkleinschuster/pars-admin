<?php

namespace Pars\Admin\Cms\Menu;

use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;

/**
 * Class CmsMenuController
 * @package Pars\Admin\Cms\Menu
 * @method CmsMenuModel getModel()
 */
class CmsSubMenuController extends CmsMenuController
{
    public function indexAction()
    {
        $this->getModel()->getBeanFinder()->getBeanLoader()->unsetValue('CmsMenu_ID');
        $this->getModel()->getBeanFinder()->setCmsMenu_ID_Parent($this->getControllerRequest()->getId()->getAttribute('CmsMenu_ID'));
        return parent::indexAction();
    }

    protected function initSubcontroller()
    {

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
        $detail = new CmsSubMenuDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $detail->setPreviewPath($this->getModel()->generatePreviewPath());
        return $detail;
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
