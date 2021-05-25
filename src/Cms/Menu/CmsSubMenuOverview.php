<?php

namespace Pars\Admin\Cms\Menu;

class CmsSubMenuOverview extends CmsMenuOverview
{
    protected function initName()
    {
        parent::initName();
        $this->setName($this->translate('submenu.overview'));
    }


    protected function initialize()
    {
        $this->setShowType(false);
        $this->setShowMove(true);
        parent::initialize();
    }

    protected function initFields()
    {
        parent::initFields();
        $this->addField('ArticleTranslation_Name', $this->translate('articletranslation.name'));
        $this->addField('Article_Code', $this->translate('article.code'));
        $this->addField('ArticleTranslation_Code', $this->translate('articletranslation.code'));
        $this->addField('CmsMenu_Level', $this->translate('cmsmenu.level'));

    }


    protected function getController(): string
    {
        return 'cmssubmenu';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'CmsMenu_ID'
        ];
    }

    protected function getCreateIdFields(): array
    {
        return [
            'CmsMenu_ID_Parent'
        ];
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

    protected function getRedirectIdFields(): array
    {
        return [
            'CmsMenu_ID' => '{CmsMenu_ID_Parent}'
        ];
    }

    protected function getRedirectController(): string
    {
        return 'cmsmenu';
    }
}
