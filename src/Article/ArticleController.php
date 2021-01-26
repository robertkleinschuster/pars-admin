<?php

namespace Pars\Admin\Article;

use Pars\Admin\Base\CrudController;
use Pars\Admin\File\FileDetail;


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
        $detail = parent::detailAction();
        $bean = $detail->getBean();
        if (!$bean->empty('File_BeanList') && !$bean->get('File_BeanList')->isEmpty()) {
            $fileOverview = new FileDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
            $fileOverview->setShowDelete(false);
            $fileOverview->setShowEdit(true);
            $fileOverview->setShowBack(false);
            $fileOverview->setBean($bean->get('File_BeanList')->first());
            $this->getView()->getLayout()->getComponentListAfter()->push($fileOverview);
        }
        if ($bean->isset('ArticleTranslation_Code')) {
            $code = $bean->get('ArticleTranslation_Code');
            if ($code == '/') {
                $code = '';
            }
            if ($detail instanceof ArticleDetail) {
                $detail->setPreviewPath(
                    $this->getModel()->getConfig('frontend.domain') . '/' .  $this->getUserBean()->getLocale()->getUrl_Code() . "/$code?clearcache=pars"
                );
            }
        }
        $detail->setLocale_List($this->getModel()->getLocale_List());
        return $detail;
    }

    public function editAction()
    {
        $edit = parent::editAction();
        if ($this->getControllerRequest()->hasEditLocale()) {
            $this->getModel()->getTranslationDefaults($edit->getBean());
            $edit->setTranslationOnly(true);
        }
        return $edit;
    }


    public function edit_textAction()
    {
        $edit = $this->editAction();
        if ($edit instanceof ArticleEdit) {
            $edit->setTextOnly(true);
        }
        return $edit;
    }
}
