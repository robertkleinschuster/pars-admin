<?php

namespace Pars\Admin\Locale;

use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Badge;

class LocaleDetail extends BaseDetail
{
    protected function initName()
    {
        $this->setName('{Locale_Name}');
    }

    protected function initFields()
    {
        parent::initFields();
        $this->setShowDelete(false);
        $this->addSpan('Locale_Code', $this->translate('locale.code'));
        $this->addSpan('Locale_UrlCode', $this->translate('locale.urlcode'));
        $this->addSpan('Locale_Domain', $this->translate('locale.domain'));
        $active = new Badge('{Locale_Active}');
        $active->setLabel($this->translate('locale.active'));
        $active->setFormat(new LocaleActiveFieldFormat($this->getTranslator()));
        $this->pushField($active);
    }


    protected function getIndexController(): string
    {
        return 'locale';
    }

    protected function getEditController(): string
    {
        return 'locale';
    }


    protected function getEditAction(): string
    {
        return 'edit';
    }

    protected function getEditIdFields(): array
    {
        return ['Locale_Code'];
    }
}
