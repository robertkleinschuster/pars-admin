<?php

namespace Pars\Admin\Cms\Page;

use Pars\Admin\Article\ArticleController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Helper\Parameter\IdParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;


/**
 * Class CmsPageController
 * @package Pars\Admin\Cms\Page
 * @method CmsPageModel getModel()
 */
class CmsPageController extends ArticleController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmspage.create', 'cmspage.edit', 'cmspage.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmspage');
    }

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setId((new IdParameter())->addId('CmsPage_ID'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        parent::addDetailFields($detail);
        $detail->addText('CmsPageType_Code', $this->translate('CmsPagetype.code'))
            ->setChapter($this->translate('article.detail.general'))
            ->setAppendToColumnPrevious(true);
        $detail->addText('CmsPageState_Code', $this->translate('CmsPagestate.code'))
            ->setChapter($this->translate('article.detail.general'))
            ->setAppendToColumnPrevious(true);
    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addSelect('CmsPageType_Code', $this->translate('CmsPagetype.code'))
            ->setChapter($this->translate('article.edit.general'))
            ->setSelectOptions($this->getModel()->getCmsPageType_Options())
            ->setAppendToColumnPrevious(true);
        $edit->addSelect('CmsPageState_Code', $this->translate('CmsPagestate.code'))
            ->setChapter($this->translate('article.edit.general'))
            ->setSelectOptions($this->getModel()->getCmsPageState_Options())
            ->setAppendToColumnPrevious(true);
    }
}
