<?php


namespace Pars\Admin\Cms\Menu;


use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;

class CmsMenuDetail extends BaseDetail
{

    protected bool $showType = true;

    protected function initialize()
    {
        $this->setSection($this->translate('section.menu'));

        $span = new Badge('{CmsMenuState_Code}');
        $span->setLabel($this->translate('cmsmenustate.code'));
        $span->setFormat(new CmsMenuStateFieldFormat($this->getTranslator()));
        $this->append($span);
        if ($this->isShowType()){
            $span = new Span('{CmsMenuType_Code}', $this->translate('cmsmenutype.code'));
            $span->setFormat(new CmsMenuTypeFieldFormat($this->getTranslator()));
            $this->append($span);
        }

        $this->setHeadline('{ArticleTranslation_Name}');
        $this->addField('Article_Code', $this->translate('article.code'));
        $this->addField('ArticleTranslation_Code', $this->translate('articletranslation.code'));
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
