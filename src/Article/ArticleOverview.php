<?php

namespace Pars\Admin\Article;

use Pars\Admin\Base\BaseOverview;

/**
 * Class ArticleOverview
 * @package Pars\Admin\Article
 */
abstract class ArticleOverview extends BaseOverview
{
    protected function initBase()
    {
        parent::initBase();
        $this->setShowOrder(true);
    }

    protected function initFields()
    {
        parent::initFields();

        $this->addFieldOrderable('ArticleTranslation_Name', $this->translate('articletranslation.name'));
        $this->addFieldOrderable('Article_Code', $this->translate('article.code'));
        $this->addFieldOrderable('ArticleTranslation_Code', $this->translate('articletranslation.code'));

    }

}
