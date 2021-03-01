<?php

namespace Pars\Admin\Cms\Block;

use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Core\Exception\AttributeExistsException;
use Niceshops\Core\Exception\AttributeLockException;
use Niceshops\Core\Exception\AttributeNotFoundException;
use Pars\Admin\Article\ArticleController;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\ContentNavigation;
use Pars\Mvc\Exception\MvcException;

/**
 * Class CmsBlockController
 * @package Pars\Admin\Cms\Block
 * @method CmsBlockModel getModel()
 */
class CmsBlockController extends ArticleController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmsblock.create', 'cmsblock.edit', 'cmsblock.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmsblock');
    }

    /**
     * @return mixed|void
     * @throws BeanException
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('content');
        $subNavigation = new ContentNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('cmsblock');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

    /**
     * @return mixed|\Pars\Admin\Article\ArticleDetail|\Pars\Admin\Base\BaseDetail
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws BeanException
     * @throws MvcException
     * @throws \Pars\Mvc\Exception\NotFoundException
     */
    public function detailAction()
    {
        $this->getView()->set('CmsBlock_ID', (int)$this->getControllerRequest()->getId()->getAttribute('CmsBlock_ID'));
        $detail = parent::detailAction();
        switch ($detail->getBean()->get('CmsBlockType_Code')) {
            case 'contact':
                $this->pushAction(
                    'articledata',
                    'index',
                    $this->translate('section.data.contact'),
                    self::SUB_ACTION_MODE_TABBED
                );
                break;
            case 'poll':
                $this->pushAction(
                    'articledata',
                    'index',
                    $this->translate('section.data.poll'),
                    self::SUB_ACTION_MODE_TABBED
                );
                break;
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
        $edit->setStateOptions($this->getModel()->getCmsBlockState_Options());
        $edit->setTypeOptions($this->getModel()->getCmsBlockType_Options());
        return $edit;
    }
}
