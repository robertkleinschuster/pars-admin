<?php

namespace Pars\Admin\Cms\Menu;

use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\ContentNavigation;
use Pars\Admin\Base\CrudController;
use Pars\Helper\Parameter\IdParameter;
use Pars\Mvc\Exception\MvcException;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
use Pars\Pattern\Exception\AttributeNotFoundException;

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
        $subNavigation = new ContentNavigation($this->getTranslator(), $this->getUserBean());
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
            $this->getModel()->getCmsMenuType_Options(true)
        );
        $this->addFilter_Select(
            'CmsMenuState_Code',
            $this->translate('cmsmenustate.code'),
            $this->getModel()->getCmsMenuState_Options(true)
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
        $bean = $detail->getBean();
        $this->initSubcontroller($bean);
        if ($bean->isset('ArticleTranslation_Code')) {
            if ($detail instanceof CmsMenuDetail) {
                $detail->setPreviewPath($this->getModel()->generatePreviewPath($bean));
            }
        }
        return $detail;
    }

    protected function initSubcontroller($bean)
    {
        $request = $this->pushAction('cmssubmenu', 'index', $this->translate('section.menu'));
        $request->setParameter(IdParameter::fromMap(['CmsMenu_ID_Parent' => $bean->CmsMenu_ID]));
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
        return $edit;
    }
}
