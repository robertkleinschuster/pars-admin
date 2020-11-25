<?php

namespace Pars\Admin\Cms\Post;

use Pars\Admin\Article\ArticleController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Helper\Parameter\IdParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;

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

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setId((new IdParameter())->addId('CmsPost_ID'));
    }


    protected function addDetailFields(Detail $detail): void
    {
       parent::addDetailFields($detail);
        $detail->addText('CmsPostType_Code', $this->translate('cmsposttype.code'))
            ->setChapter($this->translate('article.detail.general'))
            ->setAppendToColumnPrevious(true);
        $detail->addText('CmsPostState_Code', $this->translate('cmspoststate.code'))
            ->setChapter($this->translate('article.detail.general'))
            ->setAppendToColumnPrevious(true);
    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addSelect('CmsPostType_Code', $this->translate('cmsposttype.code'))
            ->setChapter($this->translate('article.edit.general'))
            ->setSelectOptions($this->getModel()->getCmsPostType_Options())
            ->setAppendToColumnPrevious(true);
        $edit->addSelect('CmsPostState_Code', $this->translate('cmspoststate.code'))
            ->setChapter($this->translate('article.edit.general'))
            ->setSelectOptions($this->getModel()->getCmsPostState_Options())
            ->setAppendToColumnPrevious(true);
    }
}
