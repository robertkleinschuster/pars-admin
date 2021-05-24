<?php

namespace Pars\Admin\Cms\PageBlock;

use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\ContentNavigation;
use Pars\Admin\Cms\Block\CmsBlockController;
use Pars\Admin\Cms\Block\CmsBlockOverview;

/**
 * Class CmsPageBlockController
 * @package Pars\Admin\Cms\PageBlock
 * @method CmsPageBlockModel getModel()
 */
class CmsPageBlockController extends CmsBlockController
{
    /**
     * @return mixed|void
     * @throws \Pars\Pattern\Exception\AttributeExistsException
     * @throws \Pars\Pattern\Exception\AttributeLockException
     * @throws \Pars\Pattern\Exception\AttributeNotFoundException
     */
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmspageblock.create', 'cmspageblock.edit', 'cmspageblock.delete');
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmspageblock');
    }

    /**
     * @return mixed|void
     * @throws \Pars\Bean\Type\Base\BeanException
     * @throws \Pars\Pattern\Exception\AttributeExistsException
     * @throws \Pars\Pattern\Exception\AttributeLockException
     * @throws \Pars\Pattern\Exception\AttributeNotFoundException
     */
    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('content');
        $subNavigation = new ContentNavigation($this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('cmspage');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
        if (
            $this->getControllerRequest()->hasId()
            && $this->getControllerRequest()->getId()->hasAttribute('CmsPage_ID')
        ) {
            $this->getView()->set(
                'CmsPage_ID',
                (int)$this->getControllerRequest()->getId()->getAttribute('CmsPage_ID')
            );
        }
    }

    public function indexAction()
    {
        return parent::indexAction();
    }


    protected function createEdit(): BaseEdit
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
        $this->addFilter_Search($this->translate('search'), 1);
        $edit = new CmsPageBlockEdit($this->getTranslator(), $this->getUserBean());
        $overview = new CmsBlockOverview($this->getTranslator(), $this->getUserBean());
        $overview->setShowDeleteBulk(false);
        $overview->setShowCreate(false);
        $overview->setShowEdit(false);
        $overview->setShowDetail(false);
        $overview->setShowDelete(false);
        if ($this->getControllerRequest()->hasFilter() && $this->getControllerRequest()->hasSearch()) {
            $finder = $this->getModel()->getBlockBeanFinder(
                $this->getControllerRequest()->getFilter(),
                $this->getControllerRequest()->getSearch()
            );
        } elseif ($this->getControllerRequest()->hasFilter()) {
            $finder = $this->getModel()->getBlockBeanFinder($this->getControllerRequest()->getFilter());
        } elseif ($this->getControllerRequest()->hasSearch()) {
            $finder = $this->getModel()->getBlockBeanFinder(null, $this->getControllerRequest()->getSearch());
        } else {
            $finder = $this->getModel()->getBlockBeanFinder();
        }
        $paginationParameter = $this->getCurrentPagination();
        $limit = $paginationParameter->getLimit();
        $page = $paginationParameter->getPage();
        if ($limit > 0 && $page > 0) {
            $finder->limit($limit, $limit * ($page - 1));
        }

        $overview->setBeanList($finder->getBeanList());
        $this->initFilter($overview);
        $this->initPagination($overview, $finder->count());
        $edit->getForm()->push($overview);
        return $edit;
    }

    public function create_newAction()
    {
        $edit = new CmsPageBlockCreateNew( $this->getTranslator(), $this->getUserBean());
        $this->injectContext($edit);
        $edit->setStateOptions($this->getModel()->getCmsBlockState_Options());
        $edit->setTypeOptions($this->getModel()->getCmsBlockType_Options());
        $edit->setFileBeanList($this->getModel()->getFileBeanList());
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $edit->setBean($this->getModel()->getEmptyBean(array_replace($this->getControllerRequest()->getId()->getAttribute_List(), $this->getPreviousAttributes())));
        $this->getModel()->getBeanConverter()
            ->convert($edit->getBean(), $this->getPreviousAttributes())->fromArray($this->getPreviousAttributes());
        $edit->setToken($this->getTokenName(), $this->generateToken($this->getTokenName()));
        $edit->setCreate(true);
        $this->getView()->pushComponent($edit);
        return $edit;
    }
}
