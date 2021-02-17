<?php

namespace Pars\Admin\Cms\PageParagraph;

use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\ContentNavigation;
use Pars\Admin\Cms\Paragraph\CmsParagraphController;
use Pars\Admin\Cms\Paragraph\CmsParagraphOverview;

/**
 * Class CmsPageParagraphController
 * @package Pars\Admin\Cms\PageParagraph
 * @method CmsPageParagraphModel getModel()
 */
class CmsPageParagraphController extends CmsParagraphController
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
        $this->setPermissions('cmspageparagraph.create', 'cmspageparagraph.edit', 'cmspageparagraph.delete');
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmspageparagraph');
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
        $edit = new CmsPageParagraphEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $overview = new CmsParagraphOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $overview->setShowDeleteBulk(false);
        $overview->setShowCreate(false);
        $overview->setShowEdit(false);
        $overview->setShowDetail(false);
        $overview->setShowDelete(false);
        $overview->setBeanList($this->getModel()->getParagraphBeanList());
        $edit->getForm()->push($overview);
        return $edit;
    }

    public function create_newAction()
    {
        $edit = new CmsPageParagraphCreateNew($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $this->injectContext($edit);
        $edit->setStateOptions($this->getModel()->getCmsParagraphState_Options());
        $edit->setTypeOptions($this->getModel()->getCmsParagraphType_Options());
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
