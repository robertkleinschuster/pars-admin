<?php

namespace Pars\Admin\Cms\Menu;

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
        if ($this->getControllerRequest()->hasId() && $this->getControllerRequest()->getId()->hasAttribute('CmsMenu_ID_Parent')) {
            $this->getView()->set('CmsMenu_ID_Parent', (int)$this->getControllerRequest()->getId()->getAttribute('CmsMenu_ID_Parent'));
        }
    }

    public function indexAction()
    {
        $overview = parent::indexAction();
        if (count($this->getModel()->getCmsPage_Options()) == 0) {
            $overview->setShowCreate(false);
        }
        return $overview;
    }

    public function detailAction()
    {
        $detail = parent::detailAction();
        $detail->setPreviewPath($this->getModel()->getConfig('frontend.domain') . '/{ArticleTranslation_Code}?clearcache=pars');
        $id = $this->getControllerRequest()->getId()->getAttribute('CmsMenu_ID');
        $this->getView()->set('CmsMenu_ID_Parent', (int) $id);
        $this->pushAction('cmssubmenu', 'index', $this->translate('section.menu'));
        $bean =  $detail->getBean();
        if ($bean->isset('ArticleTranslation_Code')) {
            $code = $bean->get('ArticleTranslation_Code');
            if ($code == '/') {
                $code = '';
            }
            if ($detail instanceof CmsMenuDetail) {
                $detail->setPreviewPath(
                    $this->getModel()->getConfig('frontend.domain') . '/' .  $this->getUserBean()->getLocale()->getUrl_Code() . "/$code?clearcache=pars"
                );
            }
        }
        return $detail;
    }

    public function editAction()
    {
        $edit = parent::editAction();
        $edit->setStateOptions($this->getModel()->getCmsMenuState_Options());
        $edit->setTypeOptions($this->getModel()->getCmsMenuType_Options());
        $edit->setPageOptions($this->getModel()->getCmsPage_Options());
        return $edit;
    }
}
