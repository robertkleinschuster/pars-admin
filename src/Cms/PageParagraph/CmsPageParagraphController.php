<?php

namespace Pars\Admin\Cms\PageParagraph;

use Pars\Admin\Base\CrudController;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Overview\Overview;

class CmsPageParagraphController extends CrudController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('CmsPageparagraph.create', 'CmsPageparagraph.edit', 'CmsPageparagraph.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('CmsPageparagraph');
    }


    protected function addEditFields(Edit $edit): void
    {
        $edit->addSelect('CmsParagraph_ID', $this->translate('cmsparagraph.name'))
        ->setSelectOptions($this->getModel()->getParagraph_Options());
    }

    protected function addOverviewFields(Overview $overview): void
    {
        // TODO: Implement addOverviewFields() method.
    }

    protected function addDetailFields(Detail $detail): void
    {
        // TODO: Implement addDetailFields() method.
    }


    protected function getSiteRedirectPath()
    {
        return $this->getPathHelper()->setController('CmsPage')->setAction('detail')->setViewIdMap($this->getControllerRequest()->getViewIdMap());
    }
}
