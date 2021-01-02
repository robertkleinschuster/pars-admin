<?php


namespace Pars\Admin\Cms\Menu;


use Pars\Admin\Base\BaseOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;

class CmsMenuOverview extends BaseOverview
{


    protected bool $showType = true;

    protected function initialize()
    {
        $this->setSection($this->translate('section.menu'));

        $span = new Badge('{CmsMenuState_Code}');
        $span->setFormat(new CmsMenuStateFieldFormat($this->getTranslator()));
        if ($this->hasDetailPath()) {
            $span->setPath($this->getDetailPath());
            $span->addOption(Span::OPTION_DECORATION_NONE);
        }
        $this->append($span);
        if ($this->isShowType()) {
            $span = new Span('{CmsMenuType_Code}', $this->translate('cmsmenutype.code'));
            $span->setFormat(new CmsMenuTypeFieldFormat($this->getTranslator()));
            if ($this->hasDetailPath()) {
                $span->setPath($this->getDetailPath());
                $span->addOption(Span::OPTION_DECORATION_NONE);
            }
            $this->append($span);
        }

        $this->addField('ArticleTranslation_Name', $this->translate('articletranslation.name'));
        $this->addField('Article_Code', $this->translate('article.code'));
        $this->addField('ArticleTranslation_Code', $this->translate('articletranslation.code'));
        $this->setShowMove(true);
        parent::initialize();
    }


    protected function getController(): string
    {
        return 'cmsmenu';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'CmsMenu_ID'
        ];
    }

    /**
     * @return bool
     */
    public function isShowType(): bool
    {
        return $this->showType;
    }

    /**
     * @param bool $showType
     */
    public function setShowType(bool $showType): void
    {
        $this->showType = $showType;
    }


}
