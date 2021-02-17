<?php

namespace Pars\Admin\Cms\Paragraph;

use Pars\Admin\Article\ArticleController;
use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\ContentNavigation;

/**
 * Class CmsParagraphController
 * @package Pars\Admin\Cms\Paragraph
 * @method CmsParagraphModel getModel()
 */
class CmsParagraphController extends ArticleController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmsparagraph.create', 'cmsparagraph.edit', 'cmsparagraph.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmsparagraph');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('content');
        $subNavigation = new ContentNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('cmsparagraph');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

    public function editAction()
    {
        $edit = parent::editAction();
        $edit->setStateOptions($this->getModel()->getCmsParagraphState_Options());
        $edit->setTypeOptions($this->getModel()->getCmsParagraphType_Options());
        return $edit;
    }



}
