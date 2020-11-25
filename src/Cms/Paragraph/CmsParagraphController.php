<?php

namespace Pars\Admin\Cms\Paragraph;

use Pars\Admin\Article\ArticleController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Helper\Parameter\IdParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;

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

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setId((new IdParameter())->addId('CmsParagraph_ID'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        parent::addDetailFields($detail);
        $detail->addText('CmsParagraphType_Code', $this->translate('cmsparagraphtype.code'))
            ->setChapter($this->translate('article.detail.general'))
            ->setAppendToColumnPrevious(true);
        $detail->addText('CmsParagraphState_Code', $this->translate('cmsparagraphstate.code'))
            ->setChapter($this->translate('article.detail.general'))
            ->setAppendToColumnPrevious(true);
    }

    protected function addEditFields(Edit $edit): void
    {
        parent::addEditFields($edit);
        $edit->addSelect('CmsParagraphType_Code', $this->translate('cmsparagraphtype.code'))
            ->setChapter($this->translate('article.edit.general'))
            ->setSelectOptions($this->getModel()->getCmsParagraphType_Options())
            ->setAppendToColumnPrevious(true);
        $edit->addSelect('CmsParagraphState_Code', $this->translate('cmsparagraphstate.code'))
            ->setChapter($this->translate('article.edit.general'))
            ->setSelectOptions($this->getModel()->getCmsParagraphState_Options())
            ->setAppendToColumnPrevious(true);
    }
}
