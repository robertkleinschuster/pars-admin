<?php


namespace Pars\Admin\Locale;


use Pars\Admin\Base\BaseEdit;

class LocaleEdit extends BaseEdit
{
    protected function initialize()
    {
        $this->getForm()->addText('Locale_Name','{Locale_Name}', $this->translate('locale.name'));
        if ($this->isCreate()) {
            $this->getForm()->addText('Locale_Code', '{Locale_Code}', $this->translate('locale.code'));
            $this->getForm()->addHidden('Locale_Code_New', 'true');
        }
        $this->getForm()->addText('Locale_UrlCode', '{Locale_UrlCode}', $this->translate('locale.urlcode'));
        $this->getForm()->addCheckbox('Locale_Active', '{Locale_Active}', $this->translate('locale.active'));
        parent::initialize();
    }

    protected function getRedirectController(): string
    {
        return 'locale';
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

    protected function getRedirectIdFields(): array
    {
        return ['Locale_Code'];
    }


}
