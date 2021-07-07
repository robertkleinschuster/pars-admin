<?php

namespace Pars\Admin\Locale;

use Pars\Admin\Base\BaseEdit;

class LocaleEdit extends BaseEdit
{
    /**
     * @var array|null
     */
    public ?array $domain_List = null;

    protected function initBase()
    {
        parent::initBase();
        $this->getForm()->setUseColumns(false);
    }


    protected function initFields()
    {
        parent::initFields();
        $this->getForm()->addText('Locale_Name', '{Locale_Name}', $this->translate('locale.name'));
        if ($this->isCreate()) {
            $this->getForm()->addText('Locale_Code', '{Locale_Code}', $this->translate('locale.code'));
            $this->getForm()->addHidden('Locale_Code_New', 'true');
        }
        $this->getForm()->addText('Locale_UrlCode', '{Locale_UrlCode}', $this->translate('locale.urlcode'));
        if ($this->hasDomain_List()) {
            $options = array_combine($this->getDomain_List(), $this->getDomain_List());
            $this->getForm()->addSelect(
                'Locale_Domain',
                $options,
                '{Locale_Domain}',
                $this->translate('locale.domain')
            );
        } else {
            $this->getForm()->addText('Locale_Domain', '{Locale_Domain}', $this->translate('locale.domain'));
        }
        $this->getForm()->addCheckbox('Locale_Active', '{Locale_Active}', $this->translate('locale.active'));
    }


    /**
    * @return array
    */
    public function getDomain_List(): array
    {
        return $this->domain_List;
    }

    /**
    * @param array $domain_List
    *
    * @return $this
    */
    public function setDomain_List(?array $domain_List): self
    {
        $this->domain_List = $domain_List;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasDomain_List(): bool
    {
        return isset($this->domain_List);
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
