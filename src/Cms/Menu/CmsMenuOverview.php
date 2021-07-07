<?php

namespace Pars\Admin\Cms\Menu;

use Pars\Admin\Base\BaseOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Field\Span;

class CmsMenuOverview extends BaseOverview
{
    protected bool $showType = true;

    protected function initName()
    {
        $this->setName($this->translate('section.menu'));
    }


    protected function initFields()
    {
        parent::initFields();
        $this->setShowMove(true);

        $this->addFieldState('CmsMenuState_Code');

        if ($this->isShowType()) {
            $span = new Span('{CmsMenuType_Code}', $this->translate('cmsmenutype.code'));
            $span->setFormat(new CmsMenuTypeFieldFormat($this->getTranslator()));
            if ($this->hasDetailPath()) {
                $span->setPath($this->getDetailPath());
                $span->addOption(Span::OPTION_DECORATION_NONE);
            }
            $this->pushField($span);
        }
        $this->addField('CmsMenu_Name', $this->translate('cmsmenu.name'));
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
