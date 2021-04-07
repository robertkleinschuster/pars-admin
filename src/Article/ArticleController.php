<?php

namespace Pars\Admin\Article;

use Niceshops\Core\Exception\AttributeExistsException;
use Niceshops\Core\Exception\AttributeLockException;
use Niceshops\Core\Exception\AttributeNotFoundException;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\CrudController;
use Pars\Admin\File\FileDetail;
use Pars\Mvc\Exception\MvcException;
use Pars\Mvc\Exception\NotFoundException;

/**
 * Class ArticleController
 * @package Pars\Admin\Article
 */
abstract class ArticleController extends CrudController
{

    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('article.create', 'article.edit', 'article.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('article');
    }

    public function detailAction()
    {
        $this->expandCollapse = false;
        $detail = parent::detailAction();
        $bean = $detail->getBean();
        if (!$bean->empty('File_BeanList') && !$bean->get('File_BeanList')->isEmpty()) {
            $fileOverview = new FileDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
            $this->injectContext($fileOverview);
            $this->initCollapsable($fileOverview);
            $fileOverview->setShowDelete(false);
            $fileOverview->setShowEdit(true);
            $fileOverview->setShowBack(false);
            $fileOverview->setBean($bean->get('File_BeanList')->first());
            $this->getView()->getLayout()->getComponentListAfter()->push($fileOverview);
        }
        if ($bean->isset('ArticleTranslation_Code')) {
            if ($detail instanceof ArticleDetail) {
                $detail->setPreviewPath($this->getModel()->generatePreviewPath($bean));
            }
        }
        $detail->setLocale_List($this->getModel()->getLocale_List());
        return $detail;
    }

    /**
     * @return BaseEdit
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws MvcException
     * @throws NotFoundException
     */
    public function editAction()
    {
        $edit = parent::editAction();
        $edit->setDomain_List($this->getModel()->getDomain_List());
        if ($this->getControllerRequest()->hasEditLocale()) {
            $this->getModel()->loadTranslationDefaults($edit->getBean());
            $edit->setTranslationOnly(true);
        }
        return $edit;
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
        $edit->setFileBeanList($this->getModel()->getFileBeanList());
        return $edit;
    }


    /**
     * @return mixed|ArticleEdit|BaseEdit
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws MvcException
     * @throws NotFoundException
     */
    public function edit_textAction()
    {
        $edit = $this->editAction();
        $edit->setFileBeanList($this->getModel()->getFileBeanList());
        if ($edit instanceof ArticleEdit) {
            $edit->setTextOnly(true);
        }
        return $edit;
    }
}
