<?php


namespace Pars\Admin\Article;


trait ArticleComponentTrait
{
    protected function getGroupByField(string $field)
    {
        switch ($field) {
            case 'ArticleTranslation_Active':
            case 'ArticleTranslation_Host':
                return $this->getGroupGeneral();
            case 'ArticleTranslation_Heading':
            case 'ArticleTranslation_SubHeading':
                return $this->getGroupHeading();
            case 'ArticleTranslation_Text':
                return $this->getGroupText();
            case 'ArticleTranslation_Footer':
                return $this->getGroupFooter();
            case 'ArticleTranslation_Keywords':
            case 'ArticleTranslation_Title':
                return $this->getGroupMeta();
            case 'ArticleTranslation_Teaser':
                return $this->getGroupMetaText();
            case 'Article_Code':
            case 'ArticleTranslation_Code':
            case 'ArticleTranslation_Name':
                return $this->getGroupIdentification();
            case 'Article_View':
            case 'Article_Read':
                return $this->getGroupStatistic();
            default:
                return $this->getGroupAdditional();
        }
    }

    protected function getGroupStatistic()
    {
        return $this->translate('article.group.statistic');
    }

    protected function getGroupAdditional()
    {
        return $this->translate('article.group.additional');
    }

    protected function getGroupIdentification()
    {
        return $this->translate('article.group.identification');
    }

    protected function getGroupMetaText()
    {
        return $this->translate('article.group.meta_text');

    }

    protected function getGroupMeta()
    {
        return $this->translate('article.group.meta');
    }

    protected function getGroupText()
    {
        return $this->translate('article.group.text');
    }
    protected function getGroupFooter()
    {
        return $this->translate('article.group.footer');
    }


    protected function getGroupHeading()
    {
        return $this->translate('article.group.heading');
    }

    protected function getGroupGeneral()
    {
        return $this->translate('article.group.general');
    }
}
