<?php

namespace Pars\Admin\Cms\PageBlock;

use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
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
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     * @throws \Niceshops\Core\Exception\AttributeNotFoundException
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
     * @throws \Niceshops\Bean\Type\Base\BeanException
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     * @throws \Niceshops\Core\Exception\AttributeNotFoundException
     */
    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('content');
        $subNavigation = new ContentNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
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

    protected function createEdit(): BaseEdit
    {
        $edit = new CmsPageBlockEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $overview = new CmsBlockOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $overview->setShowDeleteBulk(false);
        $overview->setShowCreate(false);
        $overview->setShowEdit(false);
        $overview->setShowDetail(false);
        $overview->setShowDelete(false);
        $overview->setBeanList($this->getModel()->getBlockBeanList());
        $edit->getForm()->push($overview);
        return $edit;
    }

    public function create_newAction()
    {
        $edit = new CmsPageBlockCreateNew($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $this->injectContext($edit);
        $edit->setStateOptions($this->getModel()->getCmsBlockState_Options());
        $edit->setTypeOptions($this->getModel()->getCmsBlockType_Options());
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $edit->setBean($this->getModel()->getEmptyBean(array_replace($this->getControllerRequest()->getId()->getAttribute_List(), $this->getPreviousAttributes())));
        $this->getModel()->getBeanConverter()
            ->convert($edit->getBean(), $this->getPreviousAttributes())->fromArray($this->getPreviousAttributes());
        $edit->setToken($this->generateToken('submit_token'));
        $edit->setCreate(true);
        $this->getView()->append($edit);
        return $edit;
    }
}
