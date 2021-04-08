<?php

namespace Pars\Admin\Locale;

use Pars\Admin\Base\BaseOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Helper\Parameter\PaginationParameter;
use Pars\Mvc\View\HtmlElement;

class LocaleOverview extends BaseOverview
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.locale'));
        $this->setShowDelete(false);
        $active = new Badge('{Locale_Active}');
        $active->setFormat(new LocaleActiveFieldFormat($this->getTranslator()));
        $this->append($active);
        $this->addField('Locale_Name', $this->translate('locale.name'));
        $this->addField('Locale_Code', $this->translate('locale.code'));
        $this->addField('Locale_Domain', $this->translate('locale.domain'));
        $this->setShowMove(true);

        parent::initialize();
    }

    protected function getController(): string
    {
        return 'locale';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'Locale_Code'
        ];
    }

    protected function getCreateIdFields(): array
    {
        return [];
    }
}
