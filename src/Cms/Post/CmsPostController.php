<?php

namespace Pars\Admin\Cms\Post;

use Pars\Bean\Type\Base\BeanException;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
use Pars\Pattern\Exception\AttributeNotFoundException;
use Pars\Admin\Article\ArticleController;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\ContentNavigation;
use Pars\Mvc\Exception\MvcException;

/**
 * Class CmsPostController
 * @package Pars\Admin\Cms\Post
 * @method CmsPostModel getModel()
 */
class CmsPostController extends ArticleController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmspost.create', 'cmspost.edit', 'cmspost.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmspost');
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
        $subNavigation->setActive('cmspage');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
        if ($this->getControllerRequest()->hasId() && $this->getControllerRequest()->getId()->hasAttribute('CmsPage_ID')) {
            $this->getView()->set('CmsPage_ID', (int) $this->getControllerRequest()->getId()->getAttribute('CmsPage_ID'));
        }
    }

    public function indexAction()
    {
        $this->addFilter_Select(
            'CmsPostType_Code',
            $this->translate('cmsposttype.code'),
            $this->getModel()->getCmsPostType_Options(true),
            1,
            1
        );
        $this->addFilter_Select(
            'CmsPostState_Code',
            $this->translate('cmspoststate.code'),
            $this->getModel()->getCmsPostState_Options(true),
            1,
            2
        );
        $this->addFilter_Checkbox(
            'CmsPost_Published',
            $this->translate('cmspost.published'),
            2,
            1
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
        $edit->setStateOptions($this->getModel()->getCmsPostState_Options());
        $edit->setTypeOptions($this->getModel()->getCmsPostType_Options());
        return $edit;
    }

    /**
     * @return BaseEdit
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws MvcException
     */
    public function createAction()
    {
        $edit = parent::createAction();
        $edit->setBean($this->getModel()->getEmptyBean(array_replace($this->getControllerRequest()->getId()->getAttribute_List(), $this->getPreviousAttributes())));
        return $edit;
    }

}
