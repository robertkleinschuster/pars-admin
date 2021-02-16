<?php


namespace Pars\Admin\Article;


use Pars\Admin\Base\BaseOverview;

/**
 * Class ArticleOverview
 * @package Pars\Admin\Article
 */
abstract class ArticleOverview extends BaseOverview
{
    protected function initialize()
    {
        $this->addField('ArticleTranslation_Name', $this->translate('articletranslation.name'));
        $this->addField('Article_Code', $this->translate('article.code'));
        $this->addField('ArticleTranslation_Code', $this->translate('articletranslation.code'));
        parent::initialize();
    }

}
