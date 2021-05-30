<?php

namespace Pars\Admin\Locale;

use Pars\Admin\Base\BaseOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Helper\Parameter\PaginationParameter;
use Pars\Mvc\View\ViewElement;

class LocaleOverview extends BaseOverview
{
    protected function initName()
    {
        $this->setName($this->translate('section.locale'));
    }


    protected function initFields()
    {
        parent::initFields();
        $this->setShowDelete(false);
        $this->addFieldState('Locale_Active');
        $this->addField('Locale_Name', $this->translate('locale.name'));
        $this->addField('Locale_Code', $this->translate('locale.code'));
        $this->addField('Locale_Domain', $this->translate('locale.domain'));
        $this->setShowMove(true);
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
