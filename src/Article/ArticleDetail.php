<?php


namespace Pars\Admin\Article;


use Pars\Admin\Base\BaseDetail;

abstract class ArticleDetail extends BaseDetail
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.article'));
        $this->setHeadline('{ArticleTranslation_Name}');
        $this->addField('Article_Code', $this->translate('article.code'));
        $this->addField('ArticleTranslation_Code', $this->translate('articletranslation.code'));
        $this->addField('ArticleTranslation_Title', $this->translate('articletranslation.title'));
        $this->addField('ArticleTranslation_Keywords', $this->translate('articletranslation.keywords'));
        $this->addField('ArticleTranslation_Heading', $this->translate('articletranslation.heading'));
        $this->addField('ArticleTranslation_SubHeading', $this->translate('articletranslation.subheading'));
        $this->addField('ArticleTranslation_Path', $this->translate('articletranslation.path'));
        $this->addField('ArticleTranslation_Teaser', $this->translate('articletranslation.teaser'));
        $this->addField('ArticleTranslation_Text', $this->translate('articletranslation.text'));
        $this->addField('ArticleTranslation_Footer', $this->translate('articletranslation.footer'));
        parent::initialize();
    }

}
