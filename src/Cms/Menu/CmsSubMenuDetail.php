<?php

namespace Pars\Admin\Cms\Menu;

class CmsSubMenuDetail extends CmsMenuDetail
{
    protected function initialize()
    {   $span = $this->addSpan('Article_Code', $this->translate('article.code'));
        $span->setGroup($this->translate('cmsmenu.group.general'));
        $span = $this->addSpan('ArticleTranslation_Code', $this->translate('articletranslation.code'));
        $span->setGroup($this->translate('cmsmenu.group.general'));
        $this->setShowType(false);
        parent::initialize();


    }


    protected function getIndexController(): string
    {
        return 'cmsmenu';
    }

    protected function getEditIdFields(): array
    {
        return [
            'CmsMenu_ID'
        ];
    }

    protected function getEditController(): string
    {
        return 'cmssubmenu';
    }

    protected function getIndexAction(): string
    {
        return 'detail';
    }

    protected function getIndexIdFields(): array
    {
        return [
            'CmsMenu_ID' => '{CmsMenu_ID_Parent}'
        ];
    }
}
