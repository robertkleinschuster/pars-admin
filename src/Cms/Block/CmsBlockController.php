<?php

namespace Pars\Admin\Cms\Block;

use Pars\Bean\Type\Base\BeanException;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
use Pars\Pattern\Exception\AttributeNotFoundException;
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
        $subNavigation = new ContentNavigation($this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('cmsblock');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
        if ($this->getControllerRequest()->hasId() && $this->getControllerRequest()->getId()->hasAttribute('CmsBlock_ID_Parent')) {
            $this->getView()->set('CmsBlock_ID_Parent', (int)$this->getControllerRequest()->getId()->getAttribute('CmsBlock_ID_Parent'));
        }
    }

    public function initSubcontroller()
    {
        $this->pushAction('cmssubblock', 'index', $this->translate('section.block'));
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
        if (
            $this->getControllerRequest()->hasId()
            && $this->getControllerRequest()->getId()->hasAttribute('CmsBlock_ID')
        ) {
            $id = $this->getControllerRequest()->getId()->getAttribute('CmsBlock_ID');
            $this->getView()->set('CmsBlock_ID_Parent', (int) $id);
        }
        $this->initSubcontroller();
        $detail = parent::detailAction();
        switch ($detail->getBean()->get('CmsBlockType_Code')) {
            case 'contact':
                $this->pushAction(
                    'articledata',
                    'index',
                    $this->translate('section.data.contact')
                );
                break;
            case 'poll':
                $this->pushAction(
                    'articledata',
                    'index',
                    $this->translate('section.data.poll')
                );
                break;
        }
        return $detail;
    }

    public function indexAction()
    {
        $this->addFilter_Select(
            'CmsBlockType_Code',
            $this->translate('cmsblocktype.code'),
            $this->getModel()->getCmsBlockType_Options(true),
        );
        $this->addFilter_Select(
            'CmsBlockState_Code',
            $this->translate('cmsblockstate.code'),
            $this->getModel()->getCmsBlockState_Options(true),
        );
        return parent::indexAction();
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
