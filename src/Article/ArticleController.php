<?php

namespace Pars\Admin\Article;

use Pars\Admin\Base\CrudController;
use Pars\Admin\File\FileDetail;


abstract class ArticleController extends CrudController
{

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
        return $detail;
    }
}
