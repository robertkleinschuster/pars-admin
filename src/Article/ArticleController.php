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
            $fileOverview->setShowEdit(false);
            $fileOverview->setShowBack(false);
            $fileOverview->setBean($bean->get('File_BeanList')->first());
            $this->getView()->append($fileOverview);
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
        return $detail;
    }
}
