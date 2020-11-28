<?php


namespace Pars\Admin\User;


use Pars\Admin\Base\BaseEdit;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Parameter\SubmitParameter;

class UserEdit extends BaseEdit
{

    public ?array $stateOptions = null;
    public ?array $localeOptions = null;


    protected function initialize()
    {
        $this->getForm()->addText('Person_Firstname', '{Person_Firstname}', $this->translate('person.firstname'), 1, 1);
        $this->getForm()->addText('Person_Lastname', '{Person_Lastname}', $this->translate('person.lastname'), 1, 2);
        $this->getForm()->addText('User_Username', '{User_Username}', $this->translate('user.username'), 3, 1);
        $this->getForm()->addPassword('User_Password', '', $this->translate('user.password'), 3, 2);
        $this->getForm()->addText('User_Displayname', '{User_Displayname}', $this->translate('user.displayname'), 2, 1);
        if ($this->hasStateOptions()) {
            $this->getForm()->addSelect('UserState_Code', $this->getStateOptions(), '{UserState_Code}', $this->translate('userstate.code'),4,2);
        } else {
            $this->getForm()->addHidden('UserState_Code', '{UserState_Code}');
        }
        if ($this->hasLocaleOptions()) {
            $this->getForm()->addSelect('Locale_Code', $this->getLocaleOptions(), '{Locale_Code}', $this->translate('user.locale'), 4,1);
        } else {
            $this->getForm()->addHidden('Locale_Code', '{Locale_Code}');
        }
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

    /**
    * @return array
    */
    public function getStateOptions(): array
    {
        return $this->stateOptions;
    }

    /**
    * @param array $stateOptions
    *
    * @return $this
    */
    public function setStateOptions(array $stateOptions): self
    {
        $this->stateOptions = $stateOptions;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasStateOptions(): bool
    {
        return isset($this->stateOptions);
    }

    /**
    * @return array
    */
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
