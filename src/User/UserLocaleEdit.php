<?php


namespace Pars\Admin\User;


use Pars\Admin\Base\BaseEdit;

class UserLocaleEdit extends BaseEdit
{
    public ?array $localeOptions = null;


    protected function initialize()
    {
        $this->getForm()->addSelect('Locale_Code', $this->getLocaleOptions(),'{Locale_Code}', $this->translate('user.locale'));
        parent::initialize();
    }


    protected function getRedirectController(): string
    {
        return 'user';
    }

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

    protected function getRedirectIdFields(): array
    {
        return ['Person_ID'];
    }

    public function getLocaleOptions(): array
    {
        return $this->localeOptions;
    }

    /**
     * @param array $localeOptions
     *
     * @return $this
     */
    public function setLocaleOptions(array $localeOptions): self
    {
        $this->localeOptions = $localeOptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasLocaleOptions(): bool
    {
        return isset($this->localeOptions);
    }


}
