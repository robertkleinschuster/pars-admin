<?php

namespace Pars\Admin\Cms\Menu;

use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
use Pars\Pattern\Exception\AttributeNotFoundException;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\ContentNavigation;
use Pars\Admin\Base\CrudController;
use Pars\Mvc\Exception\MvcException;

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
        $this->addFilter_Select(
            'CmsMenuType_Code',
            $this->translate('cmsmenutype.code'),
            $this->getModel()->getCmsMenuType_Options(true),
            1,
            1
        );
        $this->addFilter_Select(
            'CmsMenuState_Code',
            $this->translate('cmsmenustate.code'),
            $this->getModel()->getCmsMenuState_Options(true),
            1,
            2
        );
        $overview = parent::indexAction();
        if (count($this->getModel()->getCmsPage_Options()) == 0) {
            $overview->setShowCreate(false);
        }
        return $overview;
    }

    public function detailAction()
    {
        $detail = parent::detailAction();
        $bean =  $detail->getBean();
        if (
            $this->getControllerRequest()->hasId()
            && $this->getControllerRequest()->getId()->hasAttribute('CmsMenu_ID')
        ) {
            $id = $this->getControllerRequest()->getId()->getAttribute('CmsMenu_ID');
            $this->getView()->set('CmsMenu_ID_Parent', (int) $id);
        }
        $this->pushAction('cmssubmenu', 'index', $this->translate('section.menu'));
        if ($bean->isset('ArticleTranslation_Code')) {
            if ($detail instanceof CmsMenuDetail) {
                $detail->setPreviewPath($this->getModel()->generatePreviewPath($bean));
            }
        }
        return $detail;
    }

    /**
     * @return BaseEdit
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws MvcException
     */
    protected function createEdit(): BaseEdit
    {
        $edit = parent::createEdit();
        $edit->setStateOptions($this->getModel()->getCmsMenuState_Options());
        $edit->setTypeOptions($this->getModel()->getCmsMenuType_Options());
        $edit->setPageOptions($this->getModel()->getCmsPage_Options());
        return $edit;
    }
}
